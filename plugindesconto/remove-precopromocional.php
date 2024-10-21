<?php
/**
 * Plugin Name: Remover Preço Promocional
 * Description: Um plugin para remover o preço promocional dos produtos.
 * Version: 1.0
 * Author: Techgroweb
 */

// Evita acesso direto ao arquivo
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Função para remover os preços promocionais
function remove_sale_price() {
    if (isset($_POST['atualizar_precos'])) {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'fields' => 'ids'
        );

        $produtos_ids = get_posts($args);
        $count = 0;

        if (!empty($produtos_ids)) {
            foreach ($produtos_ids as $id) {
                $product = wc_get_product($id);

                // Verifica se o produto é variável
                if ($product->is_type('variable')) {
                    // Obtenha todas as variações
                    $variacoes_ids = $product->get_children();
                    foreach ($variacoes_ids as $variacao_id) {
                        $variacao = wc_get_product($variacao_id);

                        // Remove o preço promocional
                        $variacao->set_sale_price('');
                        $variacao->save();
                    }
                } else {
                    // Remove o preço promocional dos produtos simples
                    $product->set_sale_price('');
                    $product->save();
                }

                $count++;
            }
            echo "<div class='updated'><p>Total de produtos atualizados: $count</p></div>";
        }
    }
}

// Adiciona a página do plugin ao menu do admin
function atualiza_preco_menu() {
    add_menu_page(
        'Remover Preço Promocional',
        'Remover Preço Promocional',
        'manage_options',
        'remover-preco-promocional',
        'atualiza_preco_page'
    );
}
add_action('admin_menu', 'atualiza_preco_menu');

// Página do plugin
function atualiza_preco_page() {
    ?>
<div class="wrap">
  <h1>Remover Preço Promocional dos Produtos</h1>
  <form method="post">
    <p>
      <input type="submit" name="atualizar_precos" class="button button-primary" value="Remover Preços Promocionais">
    </p>
  </form>
  <?php remove_sale_price(); ?>
</div>
<?php
}
?>