// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import {createApp} from 'vue';
import Counter from './../templates/Component/Counter.html.twig';
import ToDoList from './../templates/Component/ToDoList.html.twig';

createApp({
    components: {
        Counter, ToDoList
    }
}).mount('#app');


