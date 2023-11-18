document.addEventListener("DOMContentLoaded", () => {
    document.getElementById('sidebarCollapse').addEventListener('click', () => {
        const sidebar = document.getElementsByClassName('sidebar')[0];
        const main = document.querySelector('main');
        const sidebarStyle = window.getComputedStyle(sidebar);
        const mainStyle = window.getComputedStyle(main);
        if (sidebarStyle.getPropertyValue("left") == '0px') {
            sidebar.style.setProperty("left", "-200px");
            main.style.setProperty("margin-left", "0");
            sidebar.querySelector('h5').style.setProperty("display", "none");
            document.getElementById('sidebarIcon').className = "bi bi-arrow-right-circle-fill"
        } else {
            sidebar.style.setProperty("left", "0");
            main.style.setProperty("margin-left", "250px");
            sidebar.querySelector('h5').style.setProperty("display", "block");
            document.getElementById('sidebarIcon').className = "bi bi-arrow-left-circle-fill"
        }
    });
});
