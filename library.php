<?php 
/*
Plugin Name: Books Library
Plugin URI: http://blackreit.com
Description: Books Library to read PDF
Author: Matthew M. Emma
Version: 1.0
Author URI: http://www.blackreit.com
*/
if( !function_exists( 'viewerjs_shortcode_handler' ) ) 
{
  add_action('admin_notices', 'books_plugin_notice');

} elseif( !function_exists( 'acf_form' ) ) 
{
  add_action('admin_notices', 'books_plugin_notice');

} elseif ( !wp_style_is('bootstrap')) 
{
  add_action('admin_notices', 'books_plugin_notice');
}

add_action('init', 'book_posttype');
add_action('init', 'register_genre_taxonomies');
add_action( 'init', 'build_genre_taxonomies' );
add_action('init', 'register_book_field_group');
add_shortcode( 'books', 'query_books');

function books_plugin_notice(){    
   echo '<div class="updated"><p>Congrats on installing the <i>Books</i> Plugin for Wordpress.  This plugin requires the following plugins and libraries to be installed:
      <ul>
        <li><a href="https://wordpress.org/plugins/advanced-custom-fields/">Advanced Custom Fields</a></li>
        <li><a href="http://viewerjs.org/releases/viewerjs-wordpress-0.5.2.zip">Viewer JS</a></li>
        <li><a href="http://getbootstrap.com/">Bootstrap Enabled Theme</a></li>
      </ul>
   </p></div>';
}

function book_posttype() {
    // Custom Post Type Labels      
    $labels = array(
      'name'               => _x( 'Books', 'post type general name' ),
      'singular_name'      => _x( 'Book', 'post type singular name' ),
      'add_new'            => _x( 'Add new', 'em_book' ),
      'add_new_item'       => __( 'Add new Book' ),
      'edit_item'          => __( 'Edit Book' ),
      'new_item'           => __( 'New Book' ),
      'all_items'          => __( 'Books' ),
      'view_item'          => __( 'View Book' ),
      'search_items'       => __( 'Search Books' ),
      'not_found'          => __( 'No Book found' ),
      'not_found_in_trash' => __( 'No Book found in trash' ),
      'parent_item_colon'  => __( 'Parent Book' ),
      'menu_name'          => __( 'Books' )
    );

    // Custom Post Type Capabilities  
    $capabilities = array(
      'edit_post'          => 'edit_post',
      'edit_posts'         => 'edit_posts',
      'edit_others_posts'  => 'edit_others_posts',
      'publish_posts'      => 'publish_posts',
      'read_post'          => 'read_post',
      'read_private_posts' => 'read_private_posts',
      'delete_post'        => 'delete_post'
    );

    // Custom Post Type Taxonomies  
    $taxonomies = array('genres');

    // Custom Post Type Supports  
    $supports = array('title', 'comments', 'revisions', 'post-formats', 'author', 'page-attributes');

    // Custom Post Type Arguments  
    $args = array(
        'labels'             => $labels,
        'hierarchical'       => true,
        'description'        => 'Books',
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_nav_menus'  => true,
        'show_in_admin_bar'  => true,
        'exclude_from_search'=> true,
        'query_var'          => true,
        'rewrite'            => false,
        'can_export'         => true,
        'has_archive'        => true,
        'menu_position'      => 25,
        'taxonomies'   => $taxonomies,
        'supports'           => $supports,
/*        'capabilities'   => $capabilities, */
        'capability_type'    => 'post',
        'menu_icon'      => '',
    );
    register_post_type('em_book', $args);
}

function register_genre_taxonomies() {
  $labels = array(
    'name'                       => _x( 'genres', 'taxonomy general name', 'emanager'),
    'singular_name'              => _x( 'Genres', 'taxonomy singular name', 'emanager'),
    'search_items'               => __( 'Search Genres', 'emanager'),
    'popular_items'              => __( 'Popular Genres', 'emanager'),
    'all_items'                  => __( 'All Genres', 'emanager'),
    'parent_item'                => __( 'Parent Genres', 'emanager'),
    'parent_item_colon'          => __( 'Parent: Genres', 'emanager'),
    'edit_item'                  => __( 'Edit Genres', 'emanager'),
    'view_item'                  => __( 'View Genres', 'emanager'),
    'update_item'                => __( 'Update Genres', 'emanager'),
    'add_new_item'               => __( 'Add New Genres', 'emanager'),
    'new_item_name'              => __( 'New Genres Name', 'emanager'),
    'add_or_remove_items'        => __( 'Add or remove Genres', 'emanager'),
    'choose_from_most_used'      => __( 'Choose from the most used Genres', 'emanager'),
    'separate_items_with_commas' => __( 'Separate Genres with commas', 'emanager'),
    'menu_name'                  => __( 'Genres', 'emanager'),
  );

  // Taxonomy Capabilities  
  $capabilities = array(
      'edit_terms'   => 'manage_categories',
      'manage_terms' => 'manage_categories',
      'delete_terms' => 'manage_categories',
      'assign_terms' => 'edit_posts'
  );

  // Linked Custom Post Types
  $cpts = array('em_book');

  // Taxonomy Arguments  
  $args = array(
      'labels'             => $labels,
      'hierarchical'       => true,
      'description'        => '',
      'public'             => true,
      'show_ui'            => true,
      'show_tagcloud'      => true,
      'show_in_nav_menus'  => false,
      'show_admin_column'  => true,
      'query_var'          => true,
      'rewrite'            => true,
/*      'capabilities'   => $capabilities, */
  );
  register_taxonomy( 'genres', $cpts, $args );
}

function build_genre_taxonomies() { 
  $parent_term = term_exists( 'genres', 'genres' ); // array is returned if taxonomy is given
  $parent_term_id = $parent_term['term_id']; // get numeric term id
  
  wp_insert_term('Science fiction','genres', array('description'=> '','slug' => 'scifi','parent'=> $parent_term_id));
  wp_insert_term('Satire','genres', array('description'=> '','slug' => 'satire','parent'=> $parent_term_id));
  wp_insert_term('Drama','genres', array('description'=> '','slug' => 'drama','parent'=> $parent_term_id));
  wp_insert_term('Romance','genres', array('description'=> '','slug' => 'romance','parent'=> $parent_term_id));
  wp_insert_term('Mystery','genres', array('description'=> '','slug' => 'mystery','parent'=> $parent_term_id));
  wp_insert_term('Horror','genres', array('description'=> '','slug' => 'horror','parent'=> $parent_term_id));
  wp_insert_term('Self help','genres', array('description'=> '','slug' => 'self-help','parent'=> $parent_term_id));
  wp_insert_term('Guide','genres', array('description'=> '','slug' => 'guide','parent'=> $parent_term_id));
  wp_insert_term('Travel','genres', array('description'=> '','slug' => 'travel','parent'=> $parent_term_id));
  wp_insert_term('Children','genres', array('description'=> '','slug' => 'children','parent'=> $parent_term_id));
  wp_insert_term('Religious','genres', array('description'=> '','slug' => 'religious','parent'=> $parent_term_id));
  wp_insert_term('Science','genres', array('description'=> '','slug' => 'science','parent'=> $parent_term_id));
  wp_insert_term('History','genres', array('description'=> '','slug' => 'history','parent'=> $parent_term_id));
  wp_insert_term('Anthologies','genres', array('description'=> '','slug' => 'anthologies','parent'=> $parent_term_id));
  wp_insert_term('Poetry','genres', array('description'=> '','slug' => 'poetry','parent'=> $parent_term_id));
  wp_insert_term('Encyclopedia','genres', array('description'=> '','slug' => 'encyclopedia','parent'=> $parent_term_id));
  wp_insert_term('Dictionary','genres', array('description'=> '','slug' => 'dictionary','parent'=> $parent_term_id));
  wp_insert_term('Comic','genres', array('description'=> '','slug' => 'comic','parent'=> $parent_term_id));
  wp_insert_term('Art','genres', array('description'=> '','slug' => 'art','parent'=> $parent_term_id));
  wp_insert_term('Cookbook','genres', array('description'=> '','slug' => 'cookbook','parent'=> $parent_term_id));
  wp_insert_term('Diary','genres', array('description'=> '','slug' => 'diary','parent'=> $parent_term_id));
  wp_insert_term('Journal','genres', array('description'=> '','slug' => 'journal','parent'=> $parent_term_id));
  wp_insert_term('Prayer','genres', array('description'=> '','slug' => 'prayer','parent'=> $parent_term_id));
  wp_insert_term('Series','genres', array('description'=> '','slug' => 'series','parent'=> $parent_term_id));
  wp_insert_term('Trilogy','genres', array('description'=> '','slug' => 'trilogy','parent'=> $parent_term_id));
  wp_insert_term('Biography','genres', array('description'=> '','slug' => 'biography','parent'=> $parent_term_id));
  wp_insert_term('Autobiography','genres', array('description'=> '','slug' => 'autobiography','parent'=> $parent_term_id));
  wp_insert_term('Fantasy','genres', array('description'=> '','slug' => 'Fantasy','parent'=> $parent_term_id));
}

function register_book_field_group() {
  if(function_exists("register_field_group"))
  {
    register_field_group(array (
      'id' => 'acf_books',
      'title' => 'Books',
      'fields' => array (
        array (
          'key' => 'field_54172059497a1',
          'label' => 'Genre',
          'name' => 'genre',
          'type' => 'taxonomy',
          'taxonomy' => 'genres',
          'field_type' => 'radio',
          'allow_null' => 0,
          'load_save_terms' => 0,
          'return_format' => 'id',
          'multiple' => 0,
        ),
        array (
          'key' => 'field_54171992401e5',
          'label' => 'ISBN',
          'name' => 'isbn',
          'type' => 'number',
          'required' => 1,
          'default_value' => '',
          'placeholder' => '',
          'prepend' => '',
          'append' => '',
          'min' => '',
          'max' => '',
          'step' => '',
        ),
        array (
          'key' => 'field_54171f8c2a2fa',
          'label' => 'Book File',
          'name' => 'book_file',
          'type' => 'file',
          'save_format' => 'url',
          'library' => 'uploadedTo',
        ),
        array (
          'key' => 'field_54171914401df',
          'label' => 'Author',
          'name' => 'author',
          'type' => 'text',
          'required' => 1,
          'default_value' => '',
          'placeholder' => '',
          'prepend' => '',
          'append' => '',
          'formatting' => 'html',
          'maxlength' => '',
        ),
        array (
          'key' => 'field_54171927401e0',
          'label' => 'Size',
          'name' => 'size',
          'type' => 'number',
          'required' => 1,
          'default_value' => '',
          'placeholder' => '',
          'prepend' => '',
          'append' => 'MB',
          'min' => '',
          'max' => '',
          'step' => '',
        ),
        array (
          'key' => 'field_5417193d401e1',
          'label' => 'Format',
          'name' => 'format',
          'type' => 'select',
          'required' => 1,
          'choices' => array (
            'PDF' => 'PDF',
            'DOC' => 'DOC',
            'TXT' => 'TXT',
          ),
          'default_value' => 'PDF',
          'allow_null' => 0,
          'multiple' => 0,
        ),
        array (
          'key' => 'field_54171973401e2',
          'label' => 'Publisher',
          'name' => 'publisher',
          'type' => 'text',
          'required' => 1,
          'default_value' => '',
          'placeholder' => '',
          'prepend' => '',
          'append' => '',
          'formatting' => 'html',
          'maxlength' => '',
        ),
        array (
          'key' => 'field_5417197b401e3',
          'label' => 'Year',
          'name' => 'year',
          'type' => 'number',
          'required' => 1,
          'default_value' => '',
          'placeholder' => '',
          'prepend' => '',
          'append' => '',
          'min' => '',
          'max' => '',
          'step' => '',
        ),
        array (
          'key' => 'field_54171986401e4',
          'label' => 'Pages',
          'name' => 'pages',
          'type' => 'number',
          'default_value' => '',
          'placeholder' => '',
          'prepend' => '',
          'append' => '',
          'min' => '',
          'max' => '',
          'step' => '',
        ),
      ),
      'location' => array (
        array (
          array (
            'param' => 'post_type',
            'operator' => '==',
            'value' => 'em_book',
            'order_no' => 0,
            'group_no' => 0,
          ),
        ),
      ),
      'options' => array (
        'position' => 'normal',
        'layout' => 'default',
        'hide_on_screen' => array (
        ),
      ),
      'menu_order' => 0,
    ));
  }

}

function query_books( $atts ) {
  extract( shortcode_atts( array(
    'search' => 'true',
  ), $atts, 'books' ) );

  if( isset($_POST['booksearch']) )
  {
  	$booksearch = $_POST['booksearch'];
    $args = array(
      'post_type' => 'em_book',
      'order' => 'ASC',
      'orderby' => 'title',
      'relation' => 'AND',
        array(
            'relation' => 'OR',
            array(
                'key' => 'title',
                'value' => $booksearch,
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'author',
                'value' => $booksearch,
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'genre',
                'value' => $booksearch,
                'compare' => 'LIKE'
            )
      ),
    );
  } else {
  	$args = array(
      'post_type' => 'em_book',
      'order' => 'ASC',
      'orderby' => 'title',
    );
  }
  $the_query = new WP_Query( $args );
  // book search form
  if ($search === 'true')
  {
    $html = '<form role="form" action="" method="post"><div class="form-group">
      <label for="booksearch">Book Search</label> <input type="text" id="booksearch" name="booksearch" value=""/>
      <button type="submit" class="btn btn-default">Find</button></div></form>';
  } 

  if ($the_query->have_posts()) {
    while ($the_query->have_posts()) {
      $the_query->the_post();

      $title = the_title();
      $bauthor = get_field('author');
      $bsize = get_field('size');
      $bformat = get_field('format');
      $bpublisher = get_field('publisher');
      $byear = get_field('year');
      $bpages = get_field('pages');
      $bisbn = get_field('isbn');
      $bfile = get_field('book_file');
      $bgenre = get_field('genre');



      $html .= '<div class="books">';
      $html .= '<div class="panel panel-default">
              <div class="panel-heading">'.$title.'<small>('.$bsize.')</small></div>
              <div class="panel-body">';
      $html .= '<strong>Author</strong>: '. $bauthor.'<br>';
      $html .= '<strong>Published by</strong>: '. $bpublisher .' in '.$byear.'<br>';
      $html .= '<strong>Genre</strong>: '. $bgenre.'<br>';
      $html .= '<strong>No. of Pages</strong>: '. $bpages.'<br>';
      $html .= '<strong>ISBN</strong>: '. $bisbn.'<br>';
      $html .= '<a href="'.$bfile.'"><button type="button" class="btn btn-primary"><i class="fa fa-book"></i> View Book</button></a>';

      $html .= '</div></div>';
    }
  }
  wp_reset_postdata();
  return $html;
}