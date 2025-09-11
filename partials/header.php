<nav class='navbar navbar-expand-lg bg-body-tertiary'>
    <div class="container-fluid">
        <h2 class="navbar-brand">Navigation</h2>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link active" href="all">Les chaussures</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?action=search">Rechercher</a></li>
            </ul>
            <form class="d-flex ms-auto" role="search" action="index.php" method="GET">
                <input type="hidden" name="action" value="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search"/>
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>