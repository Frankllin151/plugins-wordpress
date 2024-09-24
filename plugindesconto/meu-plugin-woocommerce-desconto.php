<?php
/**
 * Plugin Name: Desconto de 30%
 * Description: Um plugin para atualizar o preço de venda dos produtos.
 * Version: 1.0
 * Author: Techgroweb
 */

// Evita acesso direto ao arquivo
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Função para atualizar os preços promocionais
function atualiza_sale_price() {
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
                        $preco_regular_variacao = $variacao->get_regular_price();

                        // Certifique-se de que o preço regular está definido
                        if ($preco_regular_variacao) {
                            $preco_float_variacao = floatval($preco_regular_variacao);

                            $desconto_percentual = 30;
                            $desconto = $preco_float_variacao * ($desconto_percentual / 100);
                            $preco_final_variacao = $preco_float_variacao - $desconto;

                            // Atualiza apenas o preço promocional (sale price)
                            $variacao->set_sale_price(floatval($preco_final_variacao));
                            $variacao->save();
                        }
                    }
                } else {
                    // Atualiza os preços dos produtos simples
                    $preco_regular = $product->get_regular_price();

                    // Certifique-se de que o preço regular está definido
                    if ($preco_regular) {
                        $preco_float = floatval($preco_regular);
                        $desconto_percentual = 30;
                        $desconto = $preco_float * ($desconto_percentual / 100);
                        $preco_final = $preco_float - $desconto;

                        // Atualiza apenas o preço promocional (sale price)
                        $product->set_sale_price(floatval($preco_final));
                        $product->save();
                    }
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
        'Atualiza Preço',
        'Atualiza Preço',
        'manage_options',
        'atualiza-preco',
        'atualiza_preco_page'
    );
}
add_action('admin_menu', 'atualiza_preco_menu');

// Página do plugin
function atualiza_preco_page() {
    ?>
<div class="wrap">
  <h1>Atualiza Preço dos Produtos</h1>
  <form method="post">
    <p>
      <input type="submit" name="atualizar_precos" class="button button-primary" value="Atualizar Preços">
    </p>
  </form>
  <?php atualiza_sale_price(); ?>
</div>
<?php
}
?>