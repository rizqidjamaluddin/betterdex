<!DOCTYPE html>
<html>
<head>
    <title></title>
    {{--<link rel="stylesheet" href="{{ asset('css/uikit.min.css') }}"/>--}}
    <link rel="stylesheet" href="{{ asset('css/dex.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/dex.components.css') }}"/>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    {{--<script src="{{ asset('js/uikit.min.js') }}"></script>--}}
    <script src="{{ asset('js/underscore-min.js') }}"></script>
    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="{{ asset('js/Chart.min.js') }}"></script>

    <script src="https://use.typekit.net/enh0yyl.js"></script>
    <script>try {
            Typekit.load({async: true});
        } catch (e) {
        }</script>
</head>
<body>
&nbsp;
<div class="wrapper">
    <div class="dex-wrapper">
        <div class="" id="pokemon-search">
            <div class="poke-grid">
                <div class="pokedex-search">
                    <input type="text" autofocus v-on:keyup="search" v-el="searchBox" v-model="searchFor">
                </div>
                <div class="no-result" v-if="!subject">Not found.</div>
                <div class="pokedex-general" v-if="subject">
                    <section class="pokemon-identity">
                        <div class="pokemon-spritebox">
                            <div class="pokemon-sprite"
                                 v-bind:style="{backgroundImage: 'url(/sprites/'+subject.sprite+'.gif)'}"></div>
                        </div>
                        <div class="pokemon-header">
                            <div class="pokemon-big-name">
                                <span class="pokemon-number">@{{ subject.number }}</span> <span
                                        class="pokemon-name">@{{ subject.name }}</span>
                            </div>
                            <div class="pokemon-type">
                                <template v-for="type in subject.types">
                                    <pokemon-type :value="type" size="large"></pokemon-type>
                                </template>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </section>
                    <section class="pokemon-defenses">
                        <div class="defense-graph">
                            <type-graph v-ref:defensive v-bind:values="subject.defenses"></type-graph>
                        </div>
                        <div class="defense-type-list">

                            <div class="type-list" v-show="quadVulnerabilities.length > 0" transition="expand">
                                <strong style="color: #72FF73">4x</strong>
                                <template v-for="type in quadVulnerabilities" transition="expand">
                                    <pokemon-type :value="type"></pokemon-type>
                                </template>
                            </div>
                            <div class="type-list" v-show="doubleVulnerabilities.length > 0" transition="expand">
                                <strong style="color: #C8FF72">2x</strong>
                                <template v-for="type in doubleVulnerabilities" transition="expand">
                                    <pokemon-type :value="type"></pokemon-type>
                                </template>
                            </div>
                            <div class="type-list" v-show="halfVulnerabilities.length > 0" transition="expand">
                                <strong style="color: #FFB572">0.5x</strong>
                                <template v-for="type in halfVulnerabilities" transition="expand">
                                    <pokemon-type :value="type"></pokemon-type>
                                </template>
                            </div>
                            <div class="type-list" v-show="quarterVulnerabilities.length > 0" transition="expand">
                                <strong style="color: #FF8C72">0.25x</strong>
                                <template v-for="type in quarterVulnerabilities" transition="expand">
                                    <pokemon-type :value="type"></pokemon-type>
                                </template>
                            </div>
                            <div class="type-list" v-show="immunities.length > 0" transition="expand">
                                <strong style="color: #AF4E66">0x</strong>
                                <template v-for="type in immunities" transition="expand">
                                    <pokemon-type :value="type"></pokemon-type>
                                </template>
                            </div>

                        </div>
                        <div class="clear"> </div>

                    </section>
                </div>
            </div>
            {{--end poke-grid--}}
        </div>
    </div>
    <script src="{{ asset('js/dex/dex.js') }}"></script>

</div>
</body>
</html>