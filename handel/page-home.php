<?php /* Template Name: Home */ get_header(); ?>
<pre>
<?php 
// wc_get_products vai retorna um array com várias coisas incluido name,slug preço do produto
$products_slide = wc_get_products([
  'limit' => 6, 
  'tag' => ['slide'], 

]); 




/* variavel array aonde vai todas variavel necessária tags, slug etc
* a variavel é apenas para deixar mais limpo o codigo
* veja ela por print_r($data); 
* use o print_r($data); na ultima linha no final do php  se não vai retorna um array vazio
*/
$data = [];
// formatar_products() : dentro do functions.php
$data['slide'] = formatar_products($products_slide, "slide");


/* puxado produto mais vendido e mais recentes 
* Últimos 9 produtos ordenados
* pela data, em ordem decrescente
*/
// mais recentes/lancamentos
$products_new = wc_get_products([
  'limit' => 9,
  'orderby' => 'date',
  'order' => 'DESC'
]);
// formatar_products() : dentro do functions.php
$data["lancamentos"] = formatar_products($products_new, "medium");
// mais vendidos
// Retornas os produtos mais vendidos
$products_sales = wc_get_products([
  'limit' => 9,
  'meta_key' => 'total_sales',
  'orderby'  => 'meta_value_num',
  'order' => 'DESC',
]);
// formatar_products() : dentro do functions.php
$data["vendas"] = formatar_products($products_sales, "medium");

//puxado id da pagina ou puxado pelo nome da pagina: get_the_ID(); 
$home_id = get_the_ID(); // primeiro metodo
//get_page_by_path("home")->ID; // segundo metodo

/* puxado custom field com meta 
key (categorias -> masculino(categoria_direita) e feminino(categoria_esquerda))
* ser tive várias categoria_esquerda no terceiro paramentro da function coloque false
* vai retorna um array (false = array)
* no caso do codigo colocamos true para retorna apenas um unico (true = unico custom field)
*/
$categoria_esquerda = get_post_meta($home_id , "categoria_esquerda" , true);
$categoria_direita = get_post_meta($home_id , "categoria_direita" , true);

/*puxado termo pelo slug ou qualquer termo 
* O product_cat e um tipos de termo  foi criado pelo plugin woocommerce 
 */


// function para formatar os dados de cada categoria
 function get_product_category_data($category) {
  $cat = get_term_by('slug', $category, 'product_cat');
  $cat_id = $cat->term_id;
  $img_id = get_term_meta($cat_id, 'thumbnail_id', true);
  return [
    'name' => $cat->name,
    'id' => $cat_id,
    'link' => get_term_link($cat_id, 'product_cat'),
    'img' => wp_get_attachment_image_src($img_id, 'slide')[0]
  ];
  
 
}
$data["categorias"][$categoria_direita] = get_product_category_data($categoria_direita);
$data["categorias"][$categoria_esquerda] = get_product_category_data($categoria_esquerda);

?>
</pre>

<?php 
if(have_posts()) : while(have_posts()) : the_post(); ?>
<ul class="vantagens">
  <li>Frete Grátis</li>
  <li>Troca Fácil</li>
  <li>Até 12x</li>
</ul>
<section class="slide-wrapper">
  <ul class="slide">
    <?php foreach($data['slide'] as $product): ?>
    <li class="slide-item">
      <img src="<?= $product['img'][0] ?>" alt=" <?= $product['name'] ?>">
      <div class="slide-info">

        <span class="slide-preco"> <?= $product['price'] ?></span>
        <h2 class="slide-nome"> <?= $product['name'] ?></h2>
        <a href="<?= $product['link'] ?>" class="btn-link">Ver Produto</a>
      </div>

    </li>
    <?php endforeach; ?>
  </ul>
</section>

<!---produtos  mais recentes -->
<section class="container">
  <h1 class="subtitulo">Lançamentos</h1>
  <?php handel_product_list($data['lancamentos']); ?>
</section>
<!--Categorias-->
<section class="categorias-home container">
  <?php foreach($data["categorias"] as $categoria): ?>
  <a href="<?= $categoria["link"]; ?>">
    <img src="<?= $categoria["img"]; ?>" alt="<?= $categoria["name"]; ?>">
    <span class="btn-link"><?= $categoria["name"]; ?></span>
  </a>
  <?php endforeach; ?>
</section>
<!---produtos  mais vendidos -->
<section class="container">
  <h1 class="subtitulo">Mais Vendidos</h1>
  <?php handel_product_list($data['vendas']); ?>
</section>



<?php endwhile; endif ?>

<?php get_footer(); ?>