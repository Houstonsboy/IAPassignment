<?php
class lookup {
    public function lookup_form() {
        ?>
        <nav class="navbar bg-body-tertiary">
            <div class="container-fluid d-flex justify-content-center">
                <form action="database/dbHandler.php" method="POST" class="d-flex" role="search">
                    <input class="form-control me-2" type="search" name="username" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit" name="getUser">Search</button>
                </form>
            </div>
        </nav>
        <?php
    }
}
