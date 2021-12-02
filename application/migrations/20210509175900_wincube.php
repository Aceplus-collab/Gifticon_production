<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Wincube extends CI_Migration {

        public function up()
        {
            $this->dbforge->add_column('tbl_businesses', [
                'source' => ['type' => 'varchar(256)']
            ]);

            $this->dbforge->add_column('tbl_gifticons', [
                'wincube_id' => ['type' => 'varchar(36)', 'unique' => TRUE],
                'wincube_image' => ['type' => 'varchar(256)']
            ]);
        }

        public function down()
        {
            $this->dbforge->drop_column('tbl_businesses', 'source');
            $this->dbforge->drop_column('tbl_gifticons', 'wincube_id');
            $this->dbforge->drop_column('tbl_gifticons', 'wincube_image');
        }
}
