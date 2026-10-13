document.addEventListener("DOMContentLoaded", () => {
    const wrapper = document.querySelector('.wrapper');
    const LogInL = document.querySelector('.logIn-Link');
    const SignUpL = document.querySelector('.signUp-Link');
    const box1 = document.querySelector('.form-box1');
    const box2 = document.querySelector('.form-box2');
    const renterRadio = document.getElementById("renter");
    const lenderRadio = document.getElementById("lender");
    const boxbtn = document.querySelector('.box-btn');

    boxbtn.addEventListener('click', ()=> {

    })

    SignUpL.addEventListener('click', ()=>{
        wrapper.classList.add('active');
    });

    LogInL.addEventListener('click', ()=> {
        wrapper.classList.remove('active');
    });

    box1.addEventListener("click", ()=> {
        renterRadio.checked = true;
    });

    box2.addEventListener("click", ()=> {
        lenderRadio.checked = true;
    });
});