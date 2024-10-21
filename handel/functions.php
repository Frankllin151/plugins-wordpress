<?php
// function principal para adicionar se não o theme não funcionar corretamente
function handel_add_woocommerce_support() 
{
  add_theme_support('woocommerce');
}
add_action("after_setup_theme" ,"handel_add_woocommerce_support");

// adicionado style.css
function handel_css() {
  $file_path = get_template_directory() . "/style.css";
  $version = filemtime($file_path); // Usa a data de modificação do arquivo como versão.
  wp_register_style("handel-style", get_template_directory_uri() . "/style.css", [], $version, false);
  wp_enqueue_style("handel-style");
}
add_action('wp_enqueue_scripts', 'handel_css');

// adicionado javascript
function handel_scripts() {
  // Caminho para os scripts
  $script_path = get_template_directory() . "/js/script.js";
  $slide_path = get_template_directory() . "/js/slide.js";
  
  
  // Usando a data de modificação dos arquivos como versão
  $script_version = filemtime($script_path);
  $slide_version = filemtime($slide_path);
  
  // Registrando e enfileirando os scripts
  wp_register_script('handel-script', get_template_directory_uri() . '/js/script.js', [], $script_version, true);
  wp_enqueue_script('handel-script');

  wp_register_script('handel-slide', get_template_directory_uri() . '/js/slide.js', [], $slide_version, true);
  wp_enqueue_script('handel-slide');
}
add_action('wp_enqueue_scripts', 'handel_scripts');


// personalizar tamanho da imagem (woocommerce/wordpress);
function handel_custom_images()
{
  // update_option serve para configura/personalizar diversas configuração padrão do wordpress 
 update_option("medium_crop", 1);

  // configura dentro do codigo 
  // 1000= largura , 800 altura
  // ser a largura da imagem for 1000 x 800 não precisa passa true 
  // para adicionar crop(cortar image) o terceiro valor da add_image_size precisa ser true
//  add_image_size("slide", 1000 , 800 , true);
  // ou para  adicionar o crop(cortar) especificamente usamos um array no terceiro valor
  add_image_size("slide", 1000 , 800 , [
    "center",
    "top"
  ]);
} 
add_action("after_setup_theme","handel_custom_images" );



/// mostrar quantidade de produtos dentro de uma pagina (todos produtos em geral) 
function handel_loop_shop_per_page()
{
  return 6;
}

// "loop_shop_per_page" loop do woocommeerce para definir quantos produto dentro de uma pagina apenas (loja)
add_filter("loop_shop_per_page" , "handel_loop_shop_per_page");

// handel_product_lis e um resultado dos $dta["chaveaqui"];
// veja no archive-product.php ou page-home.php como funcionar o
// handel_product_list() 
function handel_product_list($products) {
  ?>

<ul class="products-list">

  <?php foreach($products as $product): ?>
  <li class="product-item">
    <a href="<?= $product['link'] ?>">
      <div class="product-info">
        <img src="<?= $product['img'][0] ?>" alt="<?= $product['name']; ?>">
        <h2> <?= $product['name']; ?> - <span> <?= $product['price'] ?></span></h2>
      </div>
      <div class="product-overlay">
        <span class="btn-link">Ver Mais</span>
      </div>
    </a>

  </li>
  <?php endforeach; ?>
</ul>
<?php // fecha function handel_product_list
}


/// format data produto 
// para formatar apenas as coisas que eu quero que retorne(nome,preço, descrição) vamos criar uma function
// $img_size e o tamanho da img que você vai passar  $img_size no $data["chaveaqui"];
/* ao acessa o metodo formatar_products() dentro de outro arquivo 
* sí não tive o tamanho da img ( $img_size) ela sempre vai ser medium
*/
function formatar_products($products, $img_size = "medium")
{
  $product_final = [];
foreach($products as $product){
  $product_final[] = [
    'name' => $product->get_name(), 
    'price' => $product->get_price_html(), // puxado o sale_price/price
    'link' => $product->get_permalink(),
    'img' => wp_get_attachment_image_src($product->get_image_id() , $img_size),
  ];
}
return $product_final;
}


/// removedo class do body 
function remove_some_body_class($classes){
 $woo_class = array_search("woocommerce", $classes);
 $woopage_class = array_search("woocommerce-page", $classes);
 $search = in_array("archive", $classes) || in_array("product-template-default", $classes) ;
 if($woo_class && $woopage_class && $search){
  unset($classes[$woo_class]);
  unset($classes[$woopage_class]);
 }
return $classes;
}
add_filter("body_class" , "remove_some_body_class");


//include (get_stylesheet_directory() . "/inc/user-custom-menu.php");
//include (get_stylesheet_directory() . "/inc/product-list.php");
/// customizado fields do checkout 
//include(get_stylesheet_directory() . "/inc/checkout-customizado.php");

// modificado header email do Woocommerce
/**
 * 
 * function handel_change_email_header()
{
  echo '<h2 style="text-align: center;">Mensagem Header</h2>';
}
add_action("woocommerce_email_header", "handel_change_email_header");
*/
function handel_change_email_footer_text($text)
{
  echo 'Handel 
   <ul style="list-style:none;">
    <li><a href="/">Facebook</a></li>
    <li><a href="/">Instagram</a></li>
    <li><a href="/">YouTube</a></li>
   </ul>
  ';
  
}

// formata single product dados
function format_single_product($id, $img_size = "medium")
{
  $product = wc_get_product($id);
  // puxado galeria do produto unico pelo id 
  $gallery_ids  = $product->get_gallery_image_ids();
  $gallery = [];
  if($gallery_ids){
   foreach($gallery_ids as $id){
     $gallery[] = wp_get_attachment_image_src($id, $img_size)[0];
   }
  }

 return [
  "id" => $id,
  "name" => $product->get_name(), 
  "price" => $product->get_price_html(), 
  "link" => $product->get_permalink(),
  "sku" => $product->get_sku(), 
  "description" => $product->get_description(),
  "img" => wp_get_attachment_image_src($product->get_image_id(), $img_size)[0],
  "gallery" => $gallery
 ];
}

// adicionado meta para header do produto unico
function add_open_graph_meta_tags() {
  if (is_product()) {
      global $post;
      $produtoUnico = format_single_product($post->ID);

      echo '<meta property="og:title" content="' . esc_attr($produtoUnico['name']) . '" />';
      echo '<meta property="og:description" content="' . esc_attr(wp_strip_all_tags($produtoUnico['description'])) . '" />';
      echo '<meta property="og:url" content="' . esc_url($produtoUnico['link']) . '" />';
      echo '<meta property="og:type" content="product" />';
      echo '<meta property="og:image" content="' . esc_url($produtoUnico['img']) . '" />';
      echo '<meta property="og:image:width" content="1200" />';
      echo '<meta property="og:image:height" content="630" />';
      echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />';
  }
}
add_action('wp_head', 'add_open_graph_meta_tags');