<?php get_header(); ?>

<!---Criado uma função para formatar o dado--->


<div class="container breadcrumb">
  <?php woocommerce_breadcrumb(['delimiter' => ' > ']); ?>
</div>
<!----
A função wc_print_notices() mostra notificações relevantes para o usuário, como quando o mesmo adiciona um produto ao carrinho.
--->
<div class="container notificacao">
  <?php wc_print_notices(); ?>
</div>

<main class="produto container">
  <?php if(have_posts()) : while(have_posts()) : the_post(); ?>

  <?php  $produtoUnico = format_single_product(get_the_ID());

  ?>

  <div class="product-gallery" data-gallery="gallery">
    <div class="product-gallery-list">
      <?php foreach($produtoUnico["gallery"] as $img ): ?>
      <img data-gallery="list" src="<?= $img; ?>" alt="<?= $produtoUnico["name"]; ?>">
      <?php endforeach; ?>
    </div>
    <div class="produto-gallery-main">
      <img data-gallery="main" src="<?= $produtoUnico["img"]; ?>" alt="<?php $produtoUnico["name"] ?>">
    </div>
  </div>
  <!--Parte principal do conteudo do single-product--->
  <div class="product-detail">
    <small><?= $produtoUnico["sku"]; ?></small>
    <h1><?= $produtoUnico["name"]; ?></h1>
    <p class="product-price"><?= $produtoUnico["price"]; ?></p>
    <!---botão de comprar do woocommerce nativo:woocommerce_template_single_add_to_cart()-->
    <?php woocommerce_template_single_add_to_cart(); ?>
    <h2>Descrição</h2>
    <p><?= $produtoUnico["description"]; ?></p>


  </div>
  <?php  endwhile; endif ?>
</main>
<!--função para produtos relacionado a o produto: wc_get_related_products($id, 6) -->
<?php $related_ids =  wc_get_related_products($produtoUnico["id"], 6);?>
<?php 
$relacionado = [];
foreach($related_ids as $product_id){
  $relacionado[] = wc_get_product($product_id);
}
 $productoRelacionadoFormatado = formatar_products($relacionado);
 ?>
<!--usar handel_product_list() -->
<section class="container-separador">
  <div class="container">
    <h2 class="subtitulo">Relacionados</h2>
    <?php handel_product_list($productoRelacionadoFormatado); ?>
  </div>
</section>


<?php get_footer();?>