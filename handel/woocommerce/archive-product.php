<?php get_header(); ?>


<?php
$products = [];
if(have_posts()) {
  while(have_posts()) {the_post();
    $products[] = wc_get_product(get_the_ID());
  }

}
$data = [];
// formatar_products() está dentro da functions.php
$data["products"] = formatar_products($products);
?>
<div class="container">
  <!--Breadcrumb é o termo dado a navegação que mostra o 
local em que o usuário está, levando em consideração a 
hierarquia de páginas do site.
exemplo: na paginas de categoria você vera nav assim: 
Início > Loja > Resultados da pesquisa para “elementor”,
Início > Loja ou
Início > Feminino , etc
-->
  <div class="breadcrumb">
    <?php woocommerce_breadcrumb(['delimiter' => ' > ']); ?>
  </div>
</div>

<article class="container products-archive">
  <nav class="filtros">
    <div class="filtro">
      <h3 class="filtro-titulo">Categorias</h3>
      <?php
      wp_nav_menu([
        'menu' => 'categorias-interna', 
        'menu_class' => 'filtro_cat',
       // container false sem nenhuma tag pai. será um ul direto no codigo
        'container' => false
      ]);
      ?>
    </div>
    <div class="filtro">

      <?php
      
     // Puxa uma lista com todos os atributos
$attribute_taxonomies = wc_get_attribute_taxonomies();
foreach($attribute_taxonomies as $atribute){
  print_r($atribute);
  the_widget("WC_Widget_Layered_Nav", [
    'title' => $atribute->attribute_label,
    
  ]);
}
     ?>

    </div>
    <div class="filtro">
      <h3 class="filtro-titulo">Filtrar por Preço</h3>
      <form action="" class="filtro-preco" method="get">
        <div>
          <label for="min_price">De R$</label>
          <!--$_GET vai retorna o min_price ou max_price-->
          <input required type="text" name="min_price" id="min_price" value="<?= $_GET["min_price"]; ?>">
        </div>
        <div>
          <label for="max_price">Até R$</label>
          <!--$_GET vai retorna o min_price ou max_price-->
          <input required type="text" name="max_price" id="max_price" value="<?= $_GET["max_price"]; ?>">
        </div>
        <button type="submit">Filtrar</button>
      </form>
    </div>
  </nav>

  <main class="container">
    <?php if(empty($data["products"])): ?>
    <h1>Produto não encontrado!</h1>
    <?php endif; ?>
    <!--woocommerce_catalog_ordering(); vai ativar um filtragem por oder 
    crescente, descrecente etc
    -->
    <?php woocommerce_catalog_ordering(); ?>
    <?php handel_product_list($data["products"]); ?>
    <!--paginação do wp function pra usar: get_the_posts_pagination()-->
    <?php echo get_the_posts_pagination(); ?>
  </main>
</article>

<?php get_footer();?>