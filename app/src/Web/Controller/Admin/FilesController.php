<?php

namespace Fc2blog\Web\Controller\Admin;

use Fc2blog\App;
use Fc2blog\Model\FilesModel;
use Fc2blog\Model\Model;
use Fc2blog\Web\Request;
use Fc2blog\Web\Session;

class FilesController extends AdminController
{

  /**
   * 一覧表示
   */
  public function ajax_index()
  {
    $request = Request::getInstance();
    $files_model = Model::load('Files');

    $blog_id = $this->getBlogId();
    
    // 検索条件
    $where = 'blog_id=?';
    $params = array($blog_id);

    if ($keyword=$request->get('keyword')) {
      $keyword = Model::escape_wildcard($keyword);
      $keyword = "%{$keyword}%";
      $where .= ' AND name LIKE ?';
      $params = array_merge($params, array($keyword));
    }

    // 並び順
    $order = 'created_at DESC, id DESC';
    switch ($request->get('order')) {
      default: case 'created_at_desc': break;
      case 'created_at_asc': $order = 'created_at ASC, id ASC'; break;
      case 'name_desc':      $order = 'name DESC, id DESC';     break;
      case 'name_asc':       $order = 'name ASC, id ASC';       break;
    }

    $options = array(
      'where'  => $where,
      'params' => $params,
      'limit'  => $request->get('limit', App::getPageLimit('FILE'), Request::VALID_POSITIVE_INT),
      'page'   => $request->get('page', 0, Request::VALID_UNSIGNED_INT),
      'order'  => $order,
    );
    $files = $files_model->find('all', $options);
    $paging = $files_model->getPaging($options);

    $this->set('files', $files);
    $this->set('paging', $paging);

    // ajax表示
    $this->layout = 'ajax.php';
  }

  /**
   * 新規作成
   */
  public function upload()
  {
    $request = Request::getInstance();
    /** @var FilesModel $files_model */
    $files_model = Model::load('Files');

    $blog_id = $this->getBlogId();

    Session::set('sig', App::genRandomString());
     
    // 初期表示時
    if ($request->file('file')) {
      // 新規登録処理
      $errors = array();
      $errors['file'] = $files_model->insertValidate($request->file('file'), $request->get('file'), $data_file);
      if (empty($errors['file'])) {
        $data_file['blog_id'] = $blog_id;
        $tmp_name = $data_file['tmp_name'];
        unset($data_file['tmp_name']);
        if ($id=$files_model->insert($data_file)) {
          // ファイルの移動
          $data_file['id'] = $id;
          $move_file_path = App::getUserFilePath($data_file, true);
          App::mkdir($move_file_path);
          move_uploaded_file($tmp_name, $move_file_path);

          $this->setInfoMessage(__('I have completed the upload of files'));
          $this->redirect(array('action'=>'upload'));
        }
      }

      // エラー情報の設定
      $this->setErrorMessage(__('Input error exists'));
      $this->set('errors', $errors);
      return ;
    }

    // PCの場合はajaxでファイル情報を取得するので以下の処理は不要
    if (App::isPC()) {
      return ;
    }

    // 検索条件
    $where = 'blog_id=?';
    $params = array($blog_id);

    if ($keyword=$request->get('keyword')) {
      $keyword = Model::escape_wildcard($keyword);
      $keyword = "%{$keyword}%";
      $where .= ' AND name LIKE ?';
      $params = array_merge($params, array($keyword));
    }

    // 並び順
    $order = 'created_at DESC, id DESC';
    switch ($request->get('order')) {
      default: case 'created_at_desc': break;
      case 'created_at_asc': $order = 'created_at ASC, id ASC'; break;
      case 'name_desc':      $order = 'name DESC, id DESC';     break;
      case 'name_asc':       $order = 'name ASC, id ASC';       break;
    }

    $options = array(
      'where'  => $where,
      'params' => $params,
      'limit'  => $request->get('limit', App::getPageLimit('FILE'), Request::VALID_POSITIVE_INT),
      'page'   => $request->get('page', 0, Request::VALID_UNSIGNED_INT),
      'order'  => $order,
    );
    $files = $files_model->find('all', $options);
    $paging = $files_model->getPaging($options);

    $this->set('files', $files);
    $this->set('paging', $paging);
  }

  /**
   * 編集
   */
  public function edit()
  {
    $request = Request::getInstance();
    /** @var FilesModel $files_model */
    $files_model = Model::load('Files');

    $id = $request->get('id');
    $blog_id = $this->getBlogId();

    // 詳細データの取得
    if (!$file=$files_model->findByIdAndBlogId($id, $blog_id)) {
      $this->redirect(array('action'=>'index'));
    }
    $this->set('file', $file);

    if (!$request->get('file')) {
      $request->set('file', $file);
      $back_url = $request->getReferer();
      if (!empty($back_url)) {
        $request->set('back_url', $back_url);    // 戻る用のURL
      }
      Session::set('sig', App::genRandomString());
      return ;
    }

    if (!Session::get('sig') || Session::get('sig') !== $request->get('sig')) {
      $request->clear();
      $this->redirect(array('action'=>'upload'));
    }

    // 新規登録処理
    $errors = array();
    $errors['file'] = $files_model->updateValidate($request->file('file'), $request->get('file'), $file, $data_file);
    if (empty($errors['file'])) {
      $tmp_name = isset($data_file['tmp_name']) ? $data_file['tmp_name'] : null;
      unset($data_file['tmp_name']);
      if ($files_model->updateByIdAndBlogId($data_file, $id, $blog_id)) {
        // ファイルの移動
        if (!empty($tmp_name)) {
          $data_file['id'] = $id;
          $data_file['blog_id'] = $blog_id;
          $move_file_path = App::getUserFilePath($data_file, true);
          App::deleteFile($blog_id, $id);
          move_uploaded_file($tmp_name, $move_file_path);
        }

        $this->setInfoMessage(__('I have updated the file'));
        $back_url = $request->get('back_url');
        if (!empty($back_url)) {
          $this->redirect($back_url);
        }
        $this->redirect(array('action'=>'upload'));
      }
    }

    // エラー情報の設定
    $this->setErrorMessage(__('Input error exists'));
    $this->set('errors', $errors);

    $back_url = $request->get('back_url');
    if (!empty($back_url)) {
      $request->set('back_url', $back_url);    // 戻る用のURL
    }
  }

  /**
   * 削除
   */
  public function delete()
  {
    $request = Request::getInstance();

    if (Session::get('sig') && Session::get('sig') === $request->get('sig')) {
      // 削除処理
      if (Model::load('Files')->deleteByIdsAndBlogId($request->get('id'), $this->getBlogId())) {
        $this->setInfoMessage(__('I removed the file'));
      } else {
        $this->setErrorMessage(__('I failed to remove'));
      }
    }

    // 元の画面へ戻る
    $back_url = $request->get('back_url');
    if (!empty($back_url)) {
      $this->redirect($back_url);
    }
    $this->redirect(array('action'=>'upload'));
  }

  /**
   * 削除
   */
  public function ajax_delete()
  {
    $request = Request::getInstance();

    // 削除処理
    $json = array('status'=>0);
    if (!Model::load('Files')->deleteByIdsAndBlogId($request->get('id'), $this->getBlogId())) {
      $json = array('status'=>1);
    }

    $this->layout = 'json.php';
    $this->set('json', $json);
  }

}

