</div>
</div>

<script src="<?php echo BASE_URL ?>dist-admin/js/custom.js"></script>
<script src="<?php echo BASE_URL ?>dist-admin/js/scripts.js"></script>


<?php if(isset($_SESSION['success'])): ?> 
<script>
     iziToast.success({
        message: '<?php echo $_SESSION["success"]; ?>',
        position: 'topRight'
    });
</script>
<?php unset($_SESSION['success']); endif; ?>

<!-- Error Toast -->
<?php if(isset($_SESSION['error'])): ?> 
<script>
    iziToast.error({
        message: '<?php echo $_SESSION["error"]; ?>',
        position: 'topRight'
    });
</script>
<?php unset($_SESSION['error']); endif; ?>

</body>
</html>
