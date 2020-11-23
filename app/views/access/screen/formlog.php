


<div class="box">
	<h2>E-Dnevnik</h2>
	<form action="access/login" method="POST">
		<div class="inputBox">
			<input type="text" name="login_username" required="" autocomplete="off">
			<label>Korisnik*</label>
		</div>
		<div class="inputBox">
			<input type="password" name="login_password" required="">
			<label>Lozinka*</label>
		</div>
		<input type="submit" value="Uloguj se!">
		<?php if(isset($_GET['err'])) : ?>
			<p class="text-center mt-2 text-danger font-weight-bold">
				<?php echo $_GET['err']; ?>	
			</p> 
		<?php endif; ?>
	</form>
</div>
</body>
</html>

