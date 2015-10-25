var baseGraphData = [
    {value: 0, color: '#A8A878', label: "normal"},
    {value: 0, color: '#C03028', label: "fighting"},
    {value: 0, color: '#A890F0', label: "flying"},
    {value: 0, color: '#A040A0', label: "poison"},
    {value: 0, color: '#E0C068', label: "ground"},
    {value: 0, color: '#B8A038', label: "rock"},
    {value: 0, color: '#A8B820', label: "bug"},
    {value: 0, color: '#705898', label: "ghost"},
    {value: 0, color: '#B8B8D0', label: "steel"},
    {value: 0, color: '#F08030', label: "fire"},
    {value: 0, color: '#6890F0', label: "water"},
    {value: 0, color: '#78C850', label: "grass"},
    {value: 0, color: '#F8D030', label: "electric"},
    {value: 0, color: '#F85888', label: "psychic"},
    {value: 0, color: '#98D8D8', label: "ice"},
    {value: 0, color: '#7038F8', label: "dragon"},
    {value: 0, color: '#705848', label: "dark"},
    {value: 0, color: '#EE99AC', label: "fairy"}
];
var baseTypes = [
    "normal", "fighting", "flying", "poison", "ground", "rock",
    "bug", "ghost", "steel", "fire", "water", "grass", "electric", "psychic", "ice", "dragon", "dark",
    "fairy"
];

var dexSearch = new Vue({
    el: "#pokemon-search",
    data: {
        defensiveChart: "",
        searchFor: "gallade",
        subject: {
            name: "",
            number: "",
            types: [],
            defenses: {}
        },
        pokedexList: []
    },
    components: {
        'pokemon-type': {
            props: {'value': String, 'size': {default: "small"}},
            computed: {
                class: function () {
                    return "type-size-" + this.size;
                }
            },
            template: '<span class="type type-{{ value }}" v-bind:class="[class]">{{ value }}</span>'
        },
        'type-graph': {
            data: function () {
                return {
                    graph: ""
                };
            },
            props: ['values'],
            template: '<canvas v-el:graph height="230" width="230"></canvas>',
            events: {
                'subject-change': function (subject) {
                    this.updateChart();
                }
            },
            methods: {
                updateChart: function () {
                    var current = this.values;
                    for (var type in current) {
                        this.graph.segments[baseTypes.indexOf(type)].value = current[type];
                    }
                    this.graph.update();
                }
            },
            ready: function () {
                this.graph = new Chart(this.$els.graph.getContext('2d')).PolarArea(baseGraphData, {
                    animationEasing: 'easeInOutSine',
                    animationSteps: 10,
                    showScale: false,
                    segmentStrokeColor : "#3A4654",
                    segmentStrokeWidth : 2,
                    animateScale : true
                });
            }
        }
    },

    computed: {
        quadVulnerabilities: function () {
            var elementMap = this.subject.defenses;
            return _.keys(_.pick(elementMap, function (value, key, object) {
                return value == 400;
            }));
        },
        doubleVulnerabilities: function () {
            var elementMap = this.subject.defenses;
            return _.keys(_.pick(elementMap, function (value, key, object) {
                return value == 200;
            }));
        },
        halfVulnerabilities: function () {
            var elementMap = this.subject.defenses;
            return _.keys(_.pick(elementMap, function (value, key, object) {
                return value == 50;
            }));
        },
        quarterVulnerabilities: function () {
            var elementMap = this.subject.defenses;
            return _.keys(_.pick(elementMap, function (value, key, object) {
                return value == 25;
            }));
        },
        immunities: function () {
            var elementMap = this.subject.defenses;
            return _.keys(_.pick(elementMap, function (value, key, object) {
                return value == 0;
            }));
        }
    },

    methods: {
        search: function (e) {
            var $v = this;
            var searchLength = this.searchFor.length;
            var oldSubject = this.subject;
            this.subject = _.find(this.pokedexList, function (pokemon) {
                return pokemon.name.substr(0, searchLength) == $v.searchFor.toLowerCase();
            });
            if (!this.subject) {
                this.subject = oldSubject;
            }
        },
        selectSearch: function () {
            this.$els.searchBox.select();
        }
    },

    ready: function () {
        var $v = this;
        var $data = $.get('api/pokemon').success(function (payload) {
            $v.$set('pokedexList',_.map(payload.data, function (value, key) {
                return _.extend(_.pick(value, ['number', 'name', 'types', 'sprite']), {
                    defenses: value['type-defenses']
                });
            }));
            $v.search();
        });

        this.$watch('subject', function () {
           this.$broadcast('subject-change', this.subject);
        });
    }
});