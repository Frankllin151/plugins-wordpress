<?php  $img_url = get_stylesheet_directory_uri() . "/img"; ?>
<footer class="footer">

  <img src="<?= $img_url ?>/handel-white.svg" alt="Handel">
  <div class="container footer-info">
    <section>
      <h3>Paginas</h3>
      <?php
      // puxado menu footer
      wp_nav_menu([
       'menu' => 'footer', 
       'container' => 'nav', 
       'container_class' => 'footer_menu', ]);
      ?>
    </section>
    <section>
      <h3>Redes Sociais</h3>
      <?php
      // puxado menu footer
      wp_nav_menu([
       'menu' => 'redes', 
       'container' => 'nav',
       'container_class' => 'footer_redes', ]);
      ?>
    </section>
    <section>
      <h3>Pagamentos</h3>
      <ul>
        <li>Cartão de Crédito</li>
        <li>Pix</li>
        <li>Bobletom Bancário</li>
        <li>Paguei Seguro</li>
      </ul>
    </section>
  </div>
  <?php
  $countries = WC()->countries;
  $base_address = $countries->get_base_address();
  $base_city = $countries->get_base_city();
  $base_state = $countries->get_base_state();
  $complete_address = "$base_address, $base_city, $base_state";
?>
  <small class="footer-copy">Handel &copy;<?= date('Y'); ?> - <?= $complete_address; ?></small>
</footer>
<!---wp_footer vai aparecer o painel pequeno do admin na frete do site no topo --->
<?php wp_footer(); ?>

</body>

</html>