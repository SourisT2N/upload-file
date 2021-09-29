if (top.location != self.location)
  {top.location = self.location}
$(document).ready(function() {
  if (window.matchMedia("(min-width: 992px)").matches)
  {
    $('.info').click(()=> $('.menu-box').toggleClass('active-show'));
    $('.menu-box').mouseleave(()=> $('.menu-box').removeClass('active-show'));
  }
  setTimeout(() => {
    $.ajax({
      type: "DELETE",
      url: "/api/file"
    });
  },5000);
  checkAuth();
  $('.copyright').html(`© ${new Date().getFullYear()}, made with <i class="fa fa-heart heart"></i> by Souris`);
  $('.register-footer h6').html(`© ${new Date().getFullYear()}, made with <i class="fa fa-heart heart"></i> by Souris`);
  $('h6.category-absolute').html(`© ${new Date().getFullYear()}, made with <i class="fa fa-heart heart"></i> by Souris`);
});
  