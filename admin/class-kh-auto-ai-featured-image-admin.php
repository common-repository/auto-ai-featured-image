<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://knowhalim.com
 * @since      1.0.0
 *
 * @package    Kh_Auto_Ai_Featured_Image
 * @subpackage Kh_Auto_Ai_Featured_Image/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Kh_Auto_Ai_Featured_Image
 * @subpackage Kh_Auto_Ai_Featured_Image/admin
 * @author     Halim <knowhalimofficial@gmail.com>
 */
class Kh_Auto_Ai_Featured_Image_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Kh_Auto_Ai_Featured_Image_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kh_Auto_Ai_Featured_Image_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/kh-auto-ai-featured-image-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Kh_Auto_Ai_Featured_Image_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kh_Auto_Ai_Featured_Image_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/kh-auto-ai-featured-image-admin.js', array( 'jquery' ), $this->version, false );

	}

}






function knowhalim_ai_auto_generate_image($keyword="",$postid){

	 $ai_auto_featured_image_generator_options = get_option( 'ai_auto_featured_image_generator_option_name' ); // Array of All Options
	 $api_key_0 = __($ai_auto_featured_image_generator_options['api_key_0']); // API Key
	 $pre_prompt_1 = __($ai_auto_featured_image_generator_options['pre_prompt_1']); // Pre Prompt
	 $post_prompt_2 = __($ai_auto_featured_image_generator_options['post_prompt_2']); // Post Prompt
	
    if ($api_key_0 !=""){
	$prompt = $pre_prompt_1." ".$keyword." ".$post_prompt_2; 
	$curl = curl_init();
wp_mail(get_bloginfo('admin_email'),'Image Generation Prompt','Dear Admin

An AI Image has been generated. The prompt used is as follows:
'.$prompt.'

Thank you for using my plugin,
Knowhalim');
	$the_post = '{
"prompt": "'.$prompt.'",
"negative_prompt": "nude, naked, nsfw, deformed eyes, canvas frame, cartoon, 3d, ((disfigured)), ((bad art)), ((deformed)),((extra limbs)),((close up)),((b&w)), weird colors, blurry, (((duplicate))), ((morbid)), ((mutilated)), [out of frame], extra fingers, mutated hands, ((poorly drawn hands)), ((poorly drawn face)), (((mutation))), (((deformed))), ((ugly)), blurry, ((bad anatomy)), (((bad proportions))), ((extra limbs)), cloned face, (((disfigured))), out of frame, ugly, extra limbs, (bad anatomy), gross proportions, (malformed limbs), ((missing arms)), ((missing legs)), (((extra arms))), (((extra legs))), mutated hands, (fused fingers), (too many fingers), (((long neck))), Photoshop, video game, ugly, tiling, poorly drawn hands, poorly drawn feet, poorly drawn face, out of frame, mutation, mutated, extra limbs, extra legs, extra arms, disfigured, deformed, cross-eye, body out of frame, blurry, bad art, bad anatomy, 3d render",
"width": 1024,
"height": 512,
"num_outputs": "1",
"guidance_scale": 7,
"num_inference_steps": 20,
"prompt_strength": 0.8,
"seed": -1,
"public": false,
"detail": true,
"mode": "semi",
"save": true
}';

	$args_post = array(
       'method' => 'POST',
    'timeout' => 45,
    'redirection' => 5,
    'httpversion' => '1.1',
    'blocking' => true,
	'body'        => $the_post,
	'sslverify' => false,
	'headers'     => array(
		'Content-type' => 'application/json',
		'Authorization'=> 'Token '.$api_key_0
	  ),
      'cookies' => array() 
	);

	$response = wp_remote_post( 'https://artsmart.ai/api/v1/process?type=text2img', $args_post );
	
	 $res = $response['body'];
	$returnvalue = json_decode($res,true);

if (array_key_exists("error",$returnvalue)){
    $fallback_prompt = $ai_auto_featured_image_generator_options['fallback_prompt']; // Post Prompt

    if($fallback_prompt!=$keyword){
        knowhalim_ai_auto_generate_image($fallback_prompt,$postid);
    }else {
        wp_mail(get_bloginfo('admin_email'),'Image is not generated for post id '.$postid, 'Dear admin,
        
This email is to inform you that the featured image for the Post ID : '.$postid.' is not generated. Please manually upload a featured image. If this keeps on happening, please update your fallback prompt.

Regards
Knowhalim AI Featured Image');
    }
}else{
    $theurl= $returnvalue['result']["output"][0];
knowhalim_afi_downloadimg($theurl,$postid);
}

    }
}



function knowhalim_afi_downloadimg($imageurl,$post_insert_id){
	//Add Featured Image
	// Add Featured Image to Post
	$image_url        = $imageurl; // Define the image URL here
	$image_name       = "kh_".time().'.png';
	$image_namejpg       = "kh_".time().'.jpeg';
	$upload_dir       = wp_upload_dir(); // Set upload folder
	$image_data       = file_get_contents($image_url); // Get image data

	$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
	$unique_file_name2 = wp_unique_filename( $upload_dir['path'], $image_namejpg ); // Generate unique name
	
	$filename         = basename( $unique_file_name ); // Create image file name
	$filename2         = basename( $unique_file_name2 );
	
	$thepath='';
	$jpgpath = '';
	// Check folder permission and define file location
	if( wp_mkdir_p( $upload_dir['path'] ) ) {
		$thepath = $upload_dir['path'];
		$file = $thepath. '/' . $filename;
		
	} else {
		$thepath = $upload_dir['basedir'];
		$file =$thepath . '/' . $filename;
	}
	
	$jpgpath = $thepath.'/'.$filename2;
	// Create the image  file on the server
	file_put_contents( $file, $image_data );

	// Check image file type
	$wp_filetype = wp_check_filetype( $filename, null );

	// Set attachment data
	$attachment = array(
		'post_mime_type' => $wp_filetype['type'],
		'post_title'     => sanitize_file_name( $filename ),
		'post_content'   => '',
		'post_status'    => 'inherit'
	);
	
	// Create the attachment
	$attach_id = wp_insert_attachment( $attachment, $file, $post_insert_id );

	// Include image.php
	require_once(ABSPATH . 'wp-admin/includes/image.php');

	// Define attachment metadata
	$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

	// Assign metadata to attachment
	wp_update_attachment_metadata( $attach_id, $attach_data );

	// And finally assign featured image to post
	set_post_thumbnail( $post_insert_id, $attach_id );
	add_post_meta($post_insert_id, '_thumbnail_id', $attach_id);
	update_post_meta($post_insert_id, '_thumbnail_id', $attach_id);	
	

	
	return $imgurl;
}

function knowhalim_generate_fimg($post_id, $post, $update){
	$ai_auto_featured_image_generator_options = get_option( 'ai_auto_featured_image_generator_option_name' ); // Array of All Options
	 $api_key_0 = $ai_auto_featured_image_generator_options['api_key_0']; // API Key
	if ($api_key_0!=""){
		$all_the_time = 0;
		if ($all_the_time==1){
			 if ( $post->post_type == 'post' && $post->post_status == 'publish' && empty(get_post_meta($post_id, '_thumbnail_id')) ) {
				 $gotimg = get_post_meta($post_id, '_thumbnail_id') ? get_post_meta($post_id, '_thumbnail_id'):'';
					if (!has_post_thumbnail($post_id)){
					$prompt = apply_filters('kh_autoimg_prompt',strtolower($post->post_title));
					knowhalim_ai_auto_generate_image($prompt,$post->ID);
					update_post_meta( $post_id, 'check_if_generated_img', true );

					}
			 }
		}
		if ($all_the_time==0){
			 if ( $post->post_type == 'post' && $post->post_status == 'publish' && empty(get_post_meta($post_id, 'check_if_generated_img')) ) {
				if (!has_post_thumbnail($post_id)){
				 $prompt = apply_filters('kh_autoimg_prompt',strtolower($post->post_title));
				 knowhalim_ai_auto_generate_image($prompt,$post->ID);
				 update_post_meta( $post_id, 'check_if_generated_img', true );
				}

			 }
		}
	}
}

add_action( 'wp_insert_post', 'knowhalim_generate_fimg', 10, 3 );

function knowhalim_auto_check_and_update_featuredimg(){
    $args = array(
            'post_type' => 'post',
            'posts_per_page'    => -1,
        'meta_query' => array(
            array(
            'key' => '_thumbnail_id',
            'value' => '',
            'compare' => 'NOT EXISTS'
            )
        ),
    );
    $postnoimgs = get_posts( $args );
        foreach ($postnoimgs as $post){
        $prompt = apply_filters('kh_autoimg_prompt',strtolower($post->post_title));
        knowhalim_ai_auto_generate_image($prompt,$post->ID);
        }
}

function kh_filter_prompts($prompt){
	$prompt = str_replace('"','',str_replace("'",'',trim($prompt)));
	$prompt = str_replace('covid','virus',$prompt);
	$prompt = str_replace('misinformation','lie',$prompt);
	$prompt = str_replace('naked','bare',$prompt);
	$prompt = str_replace('pornography','bad behavior',$prompt);
	$prompt = str_replace('porn','bad image',$prompt);
    $prompt = str_replace('donald trump','authority',$prompt);
    $prompt = str_replace('sex','love',$prompt);
    $prompt = str_replace('rape','love',$prompt);
    $prompt = str_replace('cocaine','pill',$prompt);
    $prompt = str_replace('drug','pill',$prompt);
    $prompt = str_replace('terror','monster',$prompt);
    $prompt = str_replace('virus','bacteria',$prompt);
    $prompt = str_replace('elon musk','elon',$prompt);
    $prompt = str_replace('stalk','follow',$prompt);
    $prompt = str_replace('criminal','bad person',$prompt);
    $prompt = str_replace('murder','take life away',$prompt);
    $prompt = str_replace('police','hero',$prompt);
    $prompt = str_replace('war','challenge',$prompt);
    $prompt = str_replace('harm','coping',$prompt);
    $prompt = str_replace('kill','left',$prompt);
    $prompt = str_replace('abuse','misused',$prompt);
    $prompt = str_replace('homicide','voluntarily take own life',$prompt);
    $prompt = str_replace('idiots','people that society do not like',$prompt);
    $prompt = str_replace('zelensky','authority',$prompt);
    $prompt = str_replace('trump','everyone favourite president',$prompt);
	return $prompt;
}
add_filter('kh_autoimg_prompt','kh_filter_prompts');

/**
 * The class
 */
function kh_get_recommends_auto_ai_featured_image(){


	$args_post = array(
       'method' => 'POST',
    'timeout' => 45,
    'redirection' => 5,
    'httpversion' => '1.1',
    'blocking' => true,
	'body'        => '{"about": "Auto AI Featured Image"}',
	'sslverify' => false,
	'headers'     => array(
		'Content-type' => 'application/json',
		'Authorization'=> 'Bearer 22jd948hhfrg'
	  ),
      'cookies' => array() 
	);


	$response = wp_remote_post( 'https://knowhalim.com/wp-json/kh_plugin/v1/recommend', $args_post );
	
	 $res = $response['body'];
	
	$returnvalue = json_decode($res,true);

	$display='<div class="recommends">'.$returnvalue['instruction'].'<h3>Other recommendations</h3>';
	foreach ($returnvalue['news'] as $item){
		$display .='<div class="kh_news">'.$item.'</div>';
	}
	$display .='</div>';
	return $display;
}
class Knowhalim_Auto_AIFeaturedImageGenerator {
	private $ai_auto_featured_image_generator_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'ai_auto_featured_image_generator_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'ai_auto_featured_image_generator_page_init' ) );
	}

	public function ai_auto_featured_image_generator_add_plugin_page() {
		add_menu_page(
			'AI Auto Featured Image Generator', // page_title
			'AI Auto Featured Image Generator', // menu_title
			'manage_options', // capability
			'ai-auto-featured-image-generator', // menu_slug
			array( $this, 'ai_featured_image_generator_create_admin_page' ), // function
			'dashicons-media-code', // icon_url
			59 // position
		);
	}

	public function ai_featured_image_generator_create_admin_page() {
		$this->ai_auto_featured_image_generator_options = get_option( 'ai_auto_featured_image_generator_option_name' ); ?>

		<div class="wrap">
			<h2>AI Featured Image Generator</h2>
			<div class="kh_option">
				<div class="kh_admin_left">
			<p>Enter your API Key, pre-prompt and post-prompt</p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'ai_auto_featured_image_generator_option_group' );
					do_settings_sections( 'ai-auto-featured-image-generator-admin' );
					submit_button();
				?>
				
				
			</form>
				
			<hr>
			<div id="status_update">
				Click button below to generate a featured image for all the post without featured image.
			</div>
			<input type="submit"  class="kh_generate_content_now" value="Scan Posts & Generate Featured Images Now" onclick="return false;" />
			</div>
				<div class="kh_admin_right">
					<?php echo kh_get_recommends_auto_ai_featured_image(); ?>
				</div>
			</div>
		</div>
	<?php }

	public function ai_auto_featured_image_generator_page_init() {
		register_setting(
			'ai_auto_featured_image_generator_option_group', // option_group
			'ai_auto_featured_image_generator_option_name', // option_name
			array( $this, 'ai_featured_image_generator_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'ai_featured_image_generator_setting_section', // id
			'AI Auto Featured Image Settings', // title
			array( $this, 'ai_featured_image_generator_section_info' ), // callback
			'ai-auto-featured-image-generator-admin' // page
		);

		add_settings_field(
			'api_key_0', // id
			'API Key', // title
			array( $this, 'api_key_0_callback' ), // callback
			'ai-auto-featured-image-generator-admin', // page
			'ai_featured_image_generator_setting_section' // section
		);

		add_settings_field(
			'pre_prompt_1', // id
			'Pre Prompt', // title
			array( $this, 'pre_prompt_1_callback' ), // callback
			'ai-auto-featured-image-generator-admin', // page
			'ai_featured_image_generator_setting_section' // section
		);

		add_settings_field(
			'post_prompt_2', // id
			'Post Prompt', // title
			array( $this, 'post_prompt_2_callback' ), // callback
			'ai-auto-featured-image-generator-admin', // page
			'ai_featured_image_generator_setting_section' // section
		);
        add_settings_field(
			'fallback_prompt', // id
			'Fallback Prompt', // title
			array( $this, 'fallback_prompt_callback' ), // callback
			'ai-auto-featured-image-generator-admin', // page
			'ai_featured_image_generator_setting_section' // section
		);
	}

	public function ai_featured_image_generator_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['api_key_0'] ) ) {
			$sanitary_values['api_key_0'] = sanitize_text_field( $input['api_key_0'] );
		}

		if ( isset( $input['pre_prompt_1'] ) ) {
			$sanitary_values['pre_prompt_1'] = sanitize_text_field( $input['pre_prompt_1'] );
		}

		if ( isset( $input['post_prompt_2'] ) ) {
			$sanitary_values['post_prompt_2'] = sanitize_text_field( $input['post_prompt_2'] );
		}

        if ( isset( $input['fallback_prompt'] ) ) {
			$sanitary_values['fallback_prompt'] = sanitize_text_field( $input['fallback_prompt'] );
		}
		
		$submit_button = sanitize_text_field( $_POST["submit"] );
         if (isset($submit_button["generate_aiimages_now"])) 
        {
      		knowhalim_auto_check_and_update_featuredimg();
        }

		return $sanitary_values;
	}

	public function ai_featured_image_generator_section_info() {
		
	}

	public function api_key_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="ai_auto_featured_image_generator_option_name[api_key_0]" id="api_key_0" value="%s">',
			isset( $this->ai_auto_featured_image_generator_options['api_key_0'] ) ? esc_attr( $this->ai_auto_featured_image_generator_options['api_key_0']) : ''
		);
	}

	public function pre_prompt_1_callback() {
		printf(
			'<input class="regular-text" type="text" name="ai_auto_featured_image_generator_option_name[pre_prompt_1]" id="pre_prompt_1" value="%s">',
			isset( $this->ai_auto_featured_image_generator_options['pre_prompt_1'] ) ? esc_attr( $this->ai_auto_featured_image_generator_options['pre_prompt_1']) : ''
		);
	}

	public function post_prompt_2_callback() {
		printf(
			'<input class="regular-text" type="text" name="ai_auto_featured_image_generator_option_name[post_prompt_2]" id="post_prompt_2" value="%s">',
			isset( $this->ai_auto_featured_image_generator_options['post_prompt_2'] ) ? esc_attr( $this->ai_auto_featured_image_generator_options['post_prompt_2']) : ''
		);
	}
    public function fallback_prompt_callback() {
		printf(
			'<input class="regular-text" type="text" name="ai_auto_featured_image_generator_option_name[fallback_prompt]" id="fallback_prompt" value="%s">',
			isset( $this->ai_auto_featured_image_generator_options['fallback_prompt'] ) ? esc_attr( $this->ai_auto_featured_image_generator_options['fallback_prompt']) : ''
		);
	}

}
if ( is_admin() )
	$kh_ai_featured_image_generator = new Knowhalim_Auto_AIFeaturedImageGenerator();



add_action( 'wp_ajax_kh_generate_content_when_click', 'kh_generate_content_when_click' );
function kh_generate_content_when_click() {
    knowhalim_auto_check_and_update_featuredimg();
	$array=array(
		'status'=>"success"
	);
	echo json_encode($array);
   die();
}
