<!DOCTYPE html>
<?php 

?>


<html>
    <head>
        <meta charset="utf-8">
        <title>Projet BDW1</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="style.css"/>

    </head>

    <header>
        <div class="row">
            
            <div class="col-3">
                <div class="p-2 bd-highlight">
                    <img src="./data/logo.jpg" class="img-fluid" alt="Responsive image" height="50px">
                </div>
            </div>
            <div class="col-6">
            <h2>Run 4 your life</h2>
            </div>


            
                <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <button type="button" class="btn btn-outline-success" id="logout">Logout</button>
                        </div>
                        <div class="p-2 bd-highlight">
                                <button type="button" class="btn btn-outline-success" id="home">Home</button>
                        </div>

                </div>
            </div>
        </div>
    </header>


    <div>
        <div class="d-flex flex-row justify-content-center">
            <form>
                <div class="form-group">
                    <label for="inputEmail"> Adresse Mail:</label>
                    <input type="email" class="form-control" id="inputEmail" placeholder="Email...">
                </div>
                <div class="form-group">
                    <label for="inputPw">Mot de passe:</label>
                    <input type="password" class="form-control" id="inputPw" placeholder="Mot de passe...">
                </div>
                <button type="submit" class="btn btn-success">Connexion</button>
            </form>
        </div>
    </div>
