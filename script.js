

const menu = document.querySelector('#mobile-menu');
const menuLinks = document.querySelector('.navbar');

const mobileMenu = () => {
    menu.classList.toggle('is-active');
    menuLinks.classList.toggle('active');

};
menu.addEventListener('click',mobileMenu);





let userbtn=document.querySelector('#user-btn');

userbtn.addEventListener('click',function(){
    let userbox=document.querySelector('.user-box');
    userbox.classList.toggle('active');
})







let iconCart=document.querySelector('.icon-cart');
let body = document.querySelector('body');
let closeCart=document.querySelector('.close');

iconCart.addEventListener('click',()=>{
    body.classList.toggle('showCart')
})
closeCart.addEventListener('click',()=>{
    body.classList.toggle('showCart')

})




let addaddress=document.querySelector('#add-address-btn');


addaddress.addEventListener('click',function(){
    let addressbox=document.querySelector('.address');
    addressbox.classList.toggle('active');
});



const ScrollRevealOption={
    distance:"50px",
    origin:"bottom",
    duration:1000,
};

ScrollReveal().reveal('.about_image img',{
    ...ScrollRevealOption,
    origin:"right",
});
ScrollReveal().reveal('.about_grid .item',{
    duration:1000,
    interval:500,
    delay:500,
    
});



// document.getElementById('add-address-btn').addEventListener('click', function() {
//     var addressForm = document.getElementById('address-form');
//     if (addressForm.style.display === 'none' || addressForm.style.display === '') {
//         addressForm.style.display = 'block';
//     } else {
//         addressForm.style.display = 'none';
//     }
// });








