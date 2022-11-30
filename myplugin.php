<?php
/*
 * Plugin Name:       My Plugin
 * Plugin URI:        https://example.com/
 * Description:       Handle the basics with this plugin.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            Milos
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       my-plugin
 * Domain Path:       /languages
 */

class WordCountPlugin{
    function __construct() {
        add_action('admin_menu', array($this, 'adminPage'));
        add_action('admin_init', array($this, 'settings'));
        add_filter('the_content', array($this, 'ifWrap'));
      }
    function ifWrap($content){
        return $this->createHTML($content);
    }

    function createHTML($content){
    $html = '<h3>' . get_option('wcp_headline', 'Post Statistic') . '</h3>';

    if (get_option( 'wcp_wordcount', '1' ) OR get_option( 'wcp_read_time', '1' )){
      $wordCount = str_word_count(strip_tags($content));
    }

    if (get_option( 'wcp_wordcount', '1' )) {
      $html .= 'This post has' . $wordCount . ' words.<br>';
    }

    if (get_option( 'wcp_charcount', '1' )){
      $html .= 'This post has' . strlen(strip_tags($content)) . ' characters.<br>';
    }

    if (get_option( 'wcp_readtime', '1' )){
      $html .= 'This post will take about ' . round($wordCount/225) . ' minutes to read.<br>';
    }

    if (get_option( 'wcp_location', '0' ) == '0'){
      return $html . $content;
    }
    return $content .$html;
    }
  


      function settings() {
        add_settings_section('wcp_first_section', null, null, 'word-count-settings-page');

        add_settings_field('wcp_location', 'Display Location', array($this, 'locationHTML'), 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_location', array('sanitize_callback' => 'sanitize_text_field', 'default' => '0'));

        add_settings_field('wcp_headline', 'Headline Text', array($this, 'headlineHTML'), 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_headline', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'Post Statistics'));

        add_settings_field('wcp_wordcount', 'Word Count', array($this, 'wordcountHTML'), 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_wordcount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

        add_settings_field('wcp_charcount', 'Character Count', array($this, 'charcountHTML'), 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_charcount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

        add_settings_field('wcp_readtime', 'Word Count', array($this, 'readtimeHTML'), 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_readtime', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
      }
      function readtimeHTML(){ ?>
        <input type="checkbox" name="wcp_readtime" value="1" <?php checked(get_option( 'wcp_readtime'), '1') ?>/>
        <?php
              }

      function charcountHTML(){ ?>
<input type="checkbox" name="wcp_charcount" value="1" <?php checked(get_option( 'wcp_charcount'), '1') ?>/>
<?php
      }

      function wordcountHTML(){ ?>
      <input type="checkbox" name="wcp_wordcount" value="1" <?php checked(get_option( 'wcp_wordcount'), '1') ?>/>
<?php
      }

function headlineHTML(){ ?>
<input type="text" name="wcp_headline" value="<?php echo esc_attr( get_option('wcp_headline') ) ?>" />
<?php
}

      function locationHTML() { ?>
        <select name="wcp_location">
          <option value="0" <?php selected(get_option( 'wcp_location' ), '0')?>>Beginning of post</option>
          <option value="1" <?php selected(get_option( 'wcp_location' ), '1')?>>End of post</option>
        </select>
      <?php }
    
      function adminPage() {
        add_options_page('Word Count Settings', 'Word Count', 'manage_options', 'word-count-settings-page', array($this, 'ourHTML'));
      }
    
    function ourHTML() { ?>
        <div class="wrap">
          <h1>Word Count Settings</h1>
          <form action="options.php" method="POST">
          <?php
            settings_fields('wordcountplugin');
            do_settings_sections('word-count-settings-page');
            submit_button();
          ?>
          </form>
        </div>
      <?php }
    }

$wordCountPlugin = new WordCountPlugin();