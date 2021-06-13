<?php 
class WCRA_Email
{
	public function __construct()
	{
	}
	public function send_email($recipients, $subject, $content)
	{
		$mail = WC()->mailer();
		$email_heading = get_bloginfo('name');
		//wcra_var_dump($mail);
		ob_start();
		$mail->email_header($email_heading);
		echo stripcslashes($content);
		$mail->email_footer();
		$message =  ob_get_contents();
		ob_end_clean(); 
		
		add_filter('wp_mail_from_name',array(&$this, 'wp_mail_from_name'));
		//add_filter('wp_mail_from', array(&$this, 'wp_mail_from'));
		$attachments = /* isset($attachment[$recipients]) ? $attachment[$recipients] : */ array();
		if(!$mail->send( $recipients, $subject, $message, "Content-Type: text/html\r\n")) //$mail->send || wp_mail
			wp_mail( $recipients, $subject, $message, "Content-Type: text/html\r\n");
		remove_filter('wp_mail_from_name',array(&$this, 'wp_mail_from_name'));
		//remove_filter('wp_mail_from',array(&$this, 'wp_mail_from'));
	}
	public function wp_mail_from_name($name) 
	{
		return get_bloginfo('name');
	}
	public function wp_mail_from($content_type) 
	{
		$server_headers = apache_request_headers();
		$domain = $server_headers['Host'] ;
		return 'noprely@'.$domain;
	}
	public function send_role_change_notification_to_user($text_per_user) //$user: WP_User
	{
		global $wcra_customer_model;
		if(!isset($text_per_user) || !is_array($text_per_user) || empty($text_per_user))
			return;
		
		foreach((array)$text_per_user as $user_id => $texts_to_send)
		{
			foreach((array)$texts_to_send as $text_and_subject)
			{
				$subject = $text_and_subject['subject'];
				$text_to_send = $text_and_subject['body'];
				//$unique_id = $text_and_subject['unique_id'];
				
				if($text_to_send == "")
					continue;
				//$user = get_userdata( $user_id );
				$user_email = $wcra_customer_model->get_user_meta( $user_id, 'billing_email' );
				if($user_email)
					$this->send_email(/* $user_info->user_email */$user_email , $subject == "" ? get_bloginfo('name'):$subject,$text_to_send);
			}
		}
	}
}
?>