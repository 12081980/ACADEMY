</div>



</main>

<footer class="footer">
    <p>&copy; 2025 E@Silva. Todos os direitos reservados.</p>

    <?php
    $uriAtual = $_SERVER['REQUEST_URI'];
    if ($uriAtual !== '/ACADEMY/public/' && $uriAtual !== '/ACADEMY/public/home') :
    ?>
        <button onclick="window.location.href='/ACADEMY/public/home'">Voltar</button>
    <?php endif; ?>
</footer>

</body>

<script src="public/js/geral.js"></script>
</html>