const modal =
document.getElementById("teamModal");

document
.getElementById("addTeamBtn")
.onclick = () =>
{
    modal.style.display = "flex";
};

document
.getElementById("closeModal")
.onclick = () =>
{
    modal.style.display = "none";
};

document
.getElementById("cancelBtn")
.onclick = () =>
{
    modal.style.display = "none";
};
