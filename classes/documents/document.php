<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of document
 *
 * @author Александр
 */
class document {

    private $id;
    private $type;
    private $title;
    private $version;
    private $path;
    private $date;
    public static $table_name = 'documents';
    public static $fields = array('id', 'type', 'title', 'path', 'date', 'version');

    public function __construct($values) {

        foreach ($values as $key => $value) {

            $this->$key = $value;
        }
    }

    public static function load($id) {

        $values = db::init()
                ->query(document::$fields)
                ->from(document::$table_name)
                ->where(array('id', '=', $id))
                ->get_row();

        if (count($values) == 0) {

            throw new InvalidArgumentException('Wrong document ID');
        }

        return new document($values);
    }

    public function get_id() {

        return $this->id;
    }

    public function get_type() {

        return $this->get_type();
    }

    public function get_title() {

        return $this->title;
    }

    public function get_path() {

        return $this->path;
    }

    public function get_date() {

        return $this->date;
    }

    public function get_version() {

        return $this->version;
    }

    public function save() {

        if (!$this->id) {

            $values = array(
                'type' => $this->type,
                'title' => $this->title,
                'path' => $this->path,
                'version' => $this->version
            );

            $this->id = db::init()
                    ->exec(document::$table_name)
                    ->values($values)
                    ->return_id('id')
                    ->insert();
        }

        db::init()
                ->exec(document::$table_name)
                ->values(array(
                    'type' => $this->type,
                    'title' => $this->title,
                    'path' => $this->path,
                    'version' => $this->version,
                    'date' => $this->date
                ))
                ->where(array('id', '=', $this->id))
                ->update();
    }

    public function delete() {

        if (!$this->id) {

            return;
        }

        db::init()->exec(document::$table_name)
                ->where(array('id', '=', $this->id))
                ->delete();

        $this->id = null;
    }

}
