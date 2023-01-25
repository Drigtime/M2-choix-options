import {createApp} from 'vue'

createApp({
    delimiters: ['${', '}$'],
    data() {
        return {
            selects: [],
        };
    },
    mounted() {
        this.selects = document.querySelectorAll('#parcours-table select');
    },
    methods: {
        updateRowColor() {
            // Iterate over the select elements
            this.selects.forEach(select => {
                // Get the parent tr element
                const tr = select.closest('tr');
                // Remove the yellow and red class
                tr.classList.remove('table-warning', 'table-danger');
                // Check the selected value and add the corresponding class
                if(select.value === '1'){
                    tr.classList.add('table-warning');
                } else if(select.value === '2'){
                    tr.classList.add('table-danger');
                }
            });
        },

    }
}).mount('#app')