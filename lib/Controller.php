<?php

namespace Svetlana\StudyProject;

use Symfony\Component\HttpFoundation\Request;

class Controller
{
    /**
     * Обработка запроса от пользователя
     *
     * todo Распарсить $_REQUEST['REQUEST_URI'] и понять какую страницу показывать
     * todo Проверку надо усложнить, чтобы учитывался http метод
     * todo Подключать хедер и футер
     *
     * @return void
     */
    public function process(): void
    {
        $request = Request::createFromGlobals();
        $page = $request->query->get('page', 1);
        switch ($page) {
            case 'form':
                $result = $this->formAction();
                break;
            case 'list':
                $result = $this->listAction();
                break;
            case 'send':
                if ($request->getMethod() == 'POST') {
                    $result = $this->sendAction();
                } else $result = null;
                break;
            default:
                header('Location: /www?page=form');
                $result = null;
        }

        if (!is_null($result)) {
            if ($request->getMethod() == 'POST') echo $result;
            else {
                require '../template/header.php';
                echo $result;
                require '../template/footer.php';
            }
        }
    }

    /**
     * Вызывается на запрос GET /form
     *
     * todo Показ формы
     *
     * @return string
     */
    protected function formAction(): string {
        $form = '';
        $form .= '<div id="send-form">
            <div class="form-div">
                <label for="name">Имя: </label>
                   <input type="text" name="name" id="name" required>
            </div>
            <div class="form-div email">
                <label for="name">Email: </label>
                   <input type="text" name="email" id="email" required>
                   <p class="hint">Не валидный email</p>
            </div>
            <div class="form-div site">
                <label for="name">Веб-страница: </label>
                   <input type="text" name="site" id="site" required>
                   <p class="hint">Не валидный url</p>
            </div>
            <div class="form-div">
                <div class="button" onclick="send_form(); return false;">Send</div><p class="success">Занесено</p>
            </div>
        </div>';
        return $form;
    }

    /**
     * Вызывается на запрос GET /list
     *
     * todo Показ списка сохранённых в базу форм
     *
     * @return string
     */
    protected function listAction(): string
    {
        // селект из базы $database->query()
        $forms = '<table class="list-table">';
        $forms .= '<tr><th>Имя</th><th>Email</th><th>Веб-страница</th></tr>';

        $database = new Database;
        $results = $database->query('SELECT * FROM forms');
        if ($results) {
            while ($row = $results->fetchArray()) {
                $forms .= '<tr><td>' . $row['name'] . '</td><td>' . $row['email'] . '</td><td>' . $row['site'] . '</td></tr>';
            }
        }

        $forms .= '</table>';
        return $forms;
    }

    /**
     * Вызывается на запрос POST /send
     *
     * todo Обработка и сохранение данных формы, ответ в формате json с обработкой на js
     *
     * @return string
     */
    protected function sendAction(): void
    {
        // запись в базу $database->query()
        $request = Request::createFromGlobals();
        $post = $request->getContent();
        $post = json_decode($post);
        $database = new Database;
        $forms = $database->exec('INSERT INTO forms (name, email, site) VALUES ("'.$post->name.'", "'.$post->email.'", "'.$post->site.'")');

        if ($forms) $response = array('status' => 'success');
        else $response = array('status' => 'error');
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }
}
