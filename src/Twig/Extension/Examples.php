<?php

namespace Hazadam\PhosphorDemo\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class Examples extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('counter_code', function(): string {
                return <<<CODE
{% vue Counter %}
    {% props %}
        {% set initialCount = 99 %}
    {% endprops %}
    <h3>{% mask %}...{% then %}{ count }{% endmask %}</h3>
    <div class="d-flex justify-content-center">
        <div class="d-flex w-50 gap-2 justify-content-center">
            <button class="btn btn-primary" type="button" @click="count--">-</button>
            <button class="btn btn-primary" type="button" @click="count++">+</button>
        </div>
    </div>
    {% block javascript %}
        import {ref} from 'vue';
        import phosphor from '@hazadam/phosphor-js';
        export default phosphor({
            props: { initialCount: Number },
            setup: (props) => {
                const count = ref(props.initialCount || 0);
                return {count};
            }
        });
    {% endblock %}
{% endvue %}
CODE;
            }),
            new TwigFunction('to_do_list_code', function(): string {
                return <<<CODE
{% vue ToDoList %}
    {% props %}
        {% set items = [
            { id: 1, label: 'Land a great new job' },
            { id: 2, label: 'Add new features to Phosphor' }
        ] %}
    {% endprops %}
    <ul class="list-group list">
        <li v-for="item in items" class="list-group-item" key="item.id">
            <div class="d-flex justify-content-between align-baseline gap-1">
                <span>{ item.label }</span>
                <button @click="del(item.id)" class="btn btn-sm btn-danger" type="button">Del</button>
            </div>
        </li>
        <input @keydown.enter="add" v-model="newItem" class="form-control" placeholder="Add new item and press Enter" type="text">
    </ul>
    {% block javascript %}
        import {ref} from 'vue';
        import phosphor from '@hazadam/phosphor-js';
        export default phosphor({
            name: 'ToDoList',
            props: { items: Array },
            setup: (props) => {
                let id = Math.max(...props.items.map(item => item.id)) || 0;
                let items = ref(props.items);
                let newItem = ref(null);
                return {
                    items,
                    newItem,
                    add: () => {
                        if (newItem.value) {
                            id++;
                            items.value.push({id, label: newItem.value});
                            newItem.value = '';
                        }
                    },
                    del: (id) => {
                        items.value = items.value.filter(item => item.id !== id);
                    }
                };
            }
        });
    {% endblock %}
{% endvue %}
CODE;
            }),
        ];
    }
}