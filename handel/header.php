<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> <?php bloginfo("name"); ?> <?php wp_title(">"); ?></title>
  <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
  <?php $img_url = get_stylesheet_directory_uri() . "/img";?>
  <header class="header">
    <a href="/"><img src="<?=  $img_url; ?>/handel.svg" alt="Handel"></a>

    <!--buscando produto/procurado-->
    <div class="busca">
      <!--Criado formulário de pesquisar no Wordpress/WooCommerce do Zero-->
      <form class="formulario" action="<?php bloginfo("url"); ?>/loja/" method="get">
        <input type="text" name="s" class="s" id="s" placeholder="Buscar" value="<?php 
        // the_search_query e uma function que pegar o que digitou 
        // exemplo buscar "teste" saira "/loja/?s=preta" na url
        the_search_query(); ?>">
        <!--o value coloque sempre product para ele buscar dentro do post-type os produtos 
        observe: name do input do button tem que sempre post_type
        a class hidden e para personalizar com css para desaparece
        -->
        <input type="text" name="post_type" value="product" class="hidden">
        <!--buscar button-->
        <input type="submit" id="searchbutton" class="searchbutton" value="Buscar">
      </form>
    </div>
    <!-- Conta-->
    <nav class="conta">
      <a class="minha-conta" href="/minha-conta">Minha Conta</a>
      <a class="carrinho" href="/carrinho">Carrinho
        <?php $cart_count = WC()->cart->get_cart_contents_count(); 
        if($cart_count){  ?>
        <span class="carrinho-count"><?php echo $cart_count ?></span>
        <?php  }?>


      </a>
    </nav>
  </header>

  <?php
wp_nav_menu([
  'menu' => 'categorias',
  // container e para mudar o tag html
  'container' => 'nav', 
  // container_class para mudar a class na tag
  'container_class' => 'menu-categorias'
]);
?>