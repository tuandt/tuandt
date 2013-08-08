<?php
/**
 * Created by Nguyen Tien Dat.
 * Date: 8/3/13
 */
?>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Soslaundry extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('winner_m');
        $this->load->model('hotel_m');
        $this->load->model('settings_m');
        $this->lang->load('winner');
        $this->load->helper('vd_debug');
    }

    /**
     * All items
     */
    public function index()
    {
        $hotels = $this->hotel_m->get_all();
        $this->template
            ->title('Register Form')
            ->set('hotels', $hotels)
            ->build('form/register');

    }
    public function form()
    {
        $params = $this->input->post();
        $emails_saved = $this->winner_m->getAllEmail();
        if(in_array($params['email'],$emails_saved)){
            redirect('?message=3');
        }
        $phone = '';
        if(isset($params['phone'])){
            $phone = implode('-',$params['phone']);
        }
        $params['phone'] = $phone;
        if($this->winner_m->create($params)){
            $email = $this->settings_m->get("server_email");
            $server_email = ($email->value != null && $email->value != '') ? $email->value : $email->default;
            /**Event send email**/
            $data = array();
            $data['to']      = $params['email'];
            $data['from']    = $server_email;
            $data['slug']    = 'user-registration-complete';
            $data['first_name']    = $params['first_name'];
            $data['subject'] = lang('soslaundry:registration_complete');

            Events::trigger('email', $data, 'array');
            redirect('?message=1');
        }else{
            redirect('?message=2');
        }
    }
    public function select_random_winners()
    {

        $winners_today = $this->winner_m->get_winner_today_data();
        //if(empty($winners_today)){
            $this->load->library('exportdataexcel');
            $this->load->helper('vd_sos');
            $email = $this->settings_m->get("server_email");
            $server_email = ($email->value != null && $email->value != '') ? $email->value : $email->default;
            $number_setting = $this->settings_m->get("number_winner");
            $number = ($number_setting->value != null && $number_setting->value != '') ? $number_setting->value : $number_setting->default;
            $winners = $this->winner_m->get_random_winner((int)$number);
            if(!empty($winners)){
                $excel = new ExportDataExcel('file');
                $filename = "winners_".date('Ymd',time()).".xls";
                $excel->filename = "export/".$filename;
                $excel->initialize();
                $header = array('','First name','Last Name','Phone','Email','Registed At','Hotel');
                $excel->addRow($header);
                foreach($winners as $i=>$w){
                    $data = array();
                    $data['to']      = $w['email'];
                    $data['from']    = $server_email;
                    $data['slug']    = 'user-is-winner';
                    $data['first_name']    = $w['first_name'];
                    $data['subject'] = lang('soslaundry:winner_win_subject');
                    Events::trigger('email', $data, 'array');
                    $this->winner_m->update($w['id'],array('winner_on' => time(),'is_winner' => 1));
                    $created_at = date('Y-m-d H:i:s',(int)$w['register_on']);
                    $row = array($i+1, $w['first_name'], $w['last_name'], $w['phone'], $w['email'],$created_at,(getHotelName($w['hotel']) != null) ? getHotelName($w['hotel'])->name : '-');
                    $excel->addRow($row);
                }
                $excel->finalize();
                /**
                 * send email report
                 */
                $email_data = array();
                $email_data['to']      = $server_email;
                $email_data['from']    = $server_email;
                $email_data['slug']    = 'report-winners';
                $email_data['subject'] = lang('soslaundry:winner_report_subject');
                $email_data['attach'][$filename] = "export/".$filename;
                Events::trigger('email', $email_data, 'array');
            }
        //}
        exit();
    }
    public function send_email_to_yesterday_winner()
    {
        $email = $this->settings_m->get("server_email");
        $server_email = ($email->value != null && $email->value != '') ? $email->value : $email->default;
        /**
         * Send email to winners yesterday
         */
        $winners_yesterday = $this->winner_m->get_winner_yesterday_data();
        if(!empty($winners_yesterday)){
            /**
             * send email
             */
            foreach($winners_yesterday as $w_y){
                $yesterday_email_data = array();
                $yesterday_email_data['to']      = $w_y->email;
                $yesterday_email_data['from']    = $server_email;
                $yesterday_email_data['slug']    = 'last-winners';
                $yesterday_email_data['subject'] = lang('soslaundry:winner_yesterday_subject');
                Events::trigger('email', $yesterday_email_data, 'array');
            }

        }
        exit();
    }
}