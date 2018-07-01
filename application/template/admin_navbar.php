      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="/admin">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="/">Return Home</a>
            </li>
            <?php if(isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
              <li class="nav-item">
                <a class="nav-link" href="/admin/index.php?action=update-password">Update Password</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="blogs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Blogs</a>
                <div class="dropdown-menu" aria-labelledby="blogs">
                  <a class="dropdown-item" href="/admin/blog">Blog Post List</a>
                  <a class="dropdown-item" href="/admin/blog/add.php">Add Blog Post</a>
                </div>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="pages" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pages</a>
                <div class="dropdown-menu" aria-labelledby="pages">
                  <a class="dropdown-item" href="/admin/page">Page List</a>
                  <a class="dropdown-item" href="/admin/page/add.php">Add Page</a>
                  <a class="dropdown-item" href="/admin/page/sort.php">Sort Page</a>
                </div>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/admin/index.php?action=logout">Logout</a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </nav>
