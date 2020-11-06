<?= $renderer->render("header") ?>
Bievennue sur la home page de mon blog

<a href="<?= $router->generateUri('blog.show', ['slug' => 'delano-de-lapastillas']) ?>">delano</a>
<?= $renderer->render("footer") ?>