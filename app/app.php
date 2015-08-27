<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Book.php";
    require_once __DIR__."/../src/Author.php";

    $app = new Silex\Application();

    $app['debug']=true;
    $server = 'mysql:host=localhost;dbname=library_catalog';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

//Landing Page
    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig');
    });

//Librarian Home
    //Loads basic page
    $app->get("/librarian_home", function() use ($app) {
        return $app['twig']->render('librarian.html.twig', array('books' => Book::getAll()));
    });

    //allows user to post new books and view them on the same page
    $app->post("/librarian_home", function() use ($app){
        $title = $_POST['title'];
        $author_first = $_POST['author_first_name'];
        $author_last = $_POST['author_last_name'];
        $book = new Book($title);
        $book -> save();
        $author = new Author($author_first, $author_last);
        $author->save();
        $author->addBook($book);

        return $app['twig']->render('librarian.html.twig', array('books' => Book::getAll(), 'authors' => Author::getAll()));
    });

    //Book page
    $app->get("/books/{id}", function($id) use ($app)
    {
        $book = Book::find($id);
        $author = $book->getAuthor();
        return $app['twig']->render('book.html.twig', array('book' => $book, 'author' => $author));
    });

    //Search for title or author
    $app->post("/search/title", function() use ($app)
    {
        $title = $_POST['search_title'];
        $books = Book::searchByTitle($title);

        return $app['twig']->render('search_results.html.twig', array('books' => $books));
    });

    $app->post("/search/author", function() use ($app)
    {
        $author = $_POST['search_author'];
        $books = Author::searchByAuthorLast($author);
        return $app['twig']->render('search_results.html.twig', array('books' => $books));
    });

    //allows user to delete all books
    $app->post("/clear_library", function() use ($app) {
        Book::deleteAll();
        return $app['twig']->render('librarian.html.twig', array('books' => Book::getAll()));
    });




    return $app;
 ?>
