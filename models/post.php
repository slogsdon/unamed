<?
class Post extends Model {
    public static $_table = 'posts';
    public static $_id_column = 'ID';

    public function __construct() {}
    public function Post() {return $this->__construct();}

    public function postmeta() {
        return $this->has_many('Postmeta', 'post_id')->find_many();
    }
};
