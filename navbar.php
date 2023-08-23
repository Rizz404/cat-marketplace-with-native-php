<nav class="navbar navbar-expand-lg bg-body-tertiary navbar-background">
  <div class="container-fluid">
    <a class="navbar-brand fs-4" href="index.php">Maxwellcat</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item me-4">
          <a class="nav-link fs-5" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item me-4">
          <a class="nav-link fs-5" href="tentang_kami.php">Tentang Kami</a>
        </li>
        <li class="nav-item me-4">
          <a class="nav-link fs-5" href="beli_kucing.php">Beli Kucing</a>
        </li>
      </ul>

      <ul class="navbar-nav ms-auto">
        <li class="nav-item me-4">
          <a class="nav-link fs-5" href="keranjang.php">Keranjang
            <?php
            if (isset($_SESSION['id'])) {
              $selectCart = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_user = '$_SESSION[id]'");
              $rowCountCart = mysqli_num_rows($selectCart); ?>
              <i class="fa-solid fa-cart-shopping"></i> <?= $rowCountCart; ?>
            <?php
            } else { ?>
              <i class="fa-solid fa-cart-shopping"></i>
            <?php } ?>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item me-4">
          <a class="nav-link fs-5" href="pesanan.php">Pesanan
            <?php
            if (isset($_SESSION['id'])) {
              $selectOrder = mysqli_query($conn, "SELECT * FROM pesanan WHERE id_user = '$_SESSION[id]'");
              $rowCountOrder = mysqli_num_rows($selectOrder); ?>
              <i class="fa fa-clipboard-list"></i> <?= $rowCountOrder; ?>
            <?php
            } else { ?>
              <i class="fa fa-clipboard-list"></i>
            <?php } ?>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav">
        <?php if (isset($_SESSION['nama_user'])) { ?>
          <li class="nav-item me-4">
            <a class="nav-link fs-3" href="akun.php">
              <i class="fa fa-user-circle"></i>
            </a>
          </li>
        <?php } else { ?>
          <li class="nav-item me-4">
            <a class="nav-link fs-5 border border-1 rounded" href="login.php">Login</a>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>