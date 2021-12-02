<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Wincube_Purchases extends CI_Migration {

        public function up()
        {
            $this->dbforge->add_column('tbl_purchases', [
                'wincube_ctr_id' => ['type' => 'varchar(64)']
            ]);
        }

        public function down()
        {
            $this->dbforge->drop_column('tbl_purchases', 'wincube_ctr_id');
        }
}
