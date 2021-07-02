$(document).ready(function () {
  startHeaderSwiper();

  $('[data-article]').click(function () {
    if ($('article.article').hasClass('open')) {
      $('article.article').removeClass('open');
    }else {
      $('article.article').addClass('open');
      var status = $(this).attr('data-article');
      getArticle(status);
    }
  });
});

function getArticle(status) {
  if (status != 'c') {
    $.ajax({
       type: "GET",
       url: '/api/' + status,
       beforeSend : function (e) {
         $('article.article').html('<a href="javascript: getArticle();" id="close" data-article="c"></a> <br> <h1> загрузка </h1>');
       },
       success: function (e) {
         $('article.article').html(e);
       }
     });
  }else {
    $('article.article').removeClass('open');
  }
}

function startHeaderSwiper() {
  const swiper = new Swiper('.swiper-container.articles_slider', {
    direction: 'horizontal',
    slidesPerView: 1,
    spaceBetween: 0,
    loop: true,

    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next.hdr',
      prevEl: '.swiper-button-prev.hdr',
    },
  });
}


function off() {}
