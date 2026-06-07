const profileIcon =
document.querySelector('.profile-icon');

const dropdown =
document.getElementById('profileDropdown');

profileIcon.addEventListener(
    'click',
    () => {
        dropdown.classList.toggle('show');
    }
);

window.addEventListener(
    'click',
    function(event){

        if(
            !event.target.closest('.profile-menu')
        ){
            dropdown.classList.remove('show');
        }

    }
);