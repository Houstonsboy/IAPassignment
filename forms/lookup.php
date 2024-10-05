<?php
class lookup {
    public function lookup_form() {
        ?>
        <style>
            body {
                height: 100vh; /* Full viewport height */
                background-image: url("assets/astronaut.jpeg");
                background-size:cover;
                background-position:center;
                color:white;
            }
            .form-container {
                background-color: #333; /* Dark background for the form */
                padding: 30px;
                border-radius: 10px;
                color: white;
            }
            .header{
                color:white;
                text-align:center;
            }
        </style>
                <h3 class="header"> search for hotel guests in translvania</h1>

                <nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid d-flex justify-content-center">
        <form action="" method="POST" class="d-flex" role="search">
            <input class="form-control me-2" type="search" name="username" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success" type="submit" name="getUser">Search</button>
        </form>
    </div>
</nav>

        <?php
    }
}
