<?php
class systemoption_model extends CI_Model {

    function __construct()
    {
        parent::__construct();		
    }
    public function getValue($value)
    {
        $this->db->select('OptionValue');
        $max_orders = $this->db->get_where('system_option', array('OptionSlug' => $value))->first_row();
        return $max_orders->OptionValue;
    }
    function getSystemOptionList()
    {
        return $this->db->get('system_option')->result();
    }
    function upateSystemOption($systemOptionData)
    {
        $this->db->update_batch('system_option', $systemOptionData, 'SystemOptionID');
    }

}
?>