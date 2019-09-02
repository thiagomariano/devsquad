<template>
    <section class="mbr-gallery mbr-slider-carousel cid-rAWCpLhEYM" id="gallery2-4">


        <div class="container">
            <!-- Gallery -->
            <div class="mbr-gallery-row">
                <div class="mbr-gallery-layout-default">
                    <div>
                        <div>
                            <div class="mbr-gallery-item mbr-gallery-item--p2" data-video-url="false"
                                 data-tags="Fantástica" v-for="(product, index) in products">

                                <div href="#lb-gallery2-4" :data-slide-to="index" data-toggle="modal" v-if="product.image">
                                    <img :src="imgSrc = 'images/product/' + product.image"
                                         alt="" title="" width="245" height="163"/>
                                    <span class="icon-focus"></span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div><!-- Lightbox -->
            <div data-app-prevent-settings="" class="mbr-slider modal fade carousel slide" tabindex="-1"
                 data-keyboard="true" data-interval="false" id="lb-gallery2-4">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="carousel-inner">


                                <div :class="index == 0 ? 'carousel-item active' : 'carousel-item' " v-for="(product, index) in products">
                                    <img :src="imgSrc = 'images/product/' + product.image" alt="" title="" v-if="product.image">
                                </div>

                            </div>
                            <a class="carousel-control carousel-control-prev" role="button" data-slide="prev"
                               href="#lb-gallery2-4">
                                <span class="mbri-left mbr-iconfont" aria-hidden="true"> </span>
                                <span class="sr-only">Previous</span>
                            </a>

                            <a class="carousel-control carousel-control-next" role="button" data-slide="next"
                               href="#lb-gallery2-4">
                                <span class="mbri-right mbr-iconfont" aria-hidden="true"></span>
                                <span class="sr-only">Next</span></a>
                            <a class="close" href="#" role="button" data-dismiss="modal">
                                <span class="sr-only">Close</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

</template>

<script>
    import axios from 'axios'

    const API_URL = 'http://localhost:8000/api';

    export default {
        mounted() {
            axios.get(`${API_URL}/products`).then((res) => {
                this.$set(this.$data, 'products', res.data.data)
            });
        },
        data() {
            return {
                products: {},
                product: {
                    name: '',
                    category_id: '',
                    price: '',
                    image: '',
                    file: '',
                    description: '',
                    id: ''
                },
            }
        }
    }
</script>
