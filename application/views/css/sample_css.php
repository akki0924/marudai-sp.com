.red {
    color: #FF0000;
}
.btn {
    cursor: pointer;
}

/*==================================================================================
loading
==================================================================================*/
<?= $const['sel_loader'] ?> {
    position: fixed;
    top: 0;
    z-index: 9999;
    width: 100%;
    height:100%;
    display: none;
    background: rgba(0,0,0,0.6);
}
<?= $const['sel_loader'] ?> <?= $const['sel_loader_cv'] ?> {
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}
<?= $const['sel_loader'] ?> <?= $const['sel_loader_spinner'] ?> {
    width: 40px;
    height: 40px;
    border: 4px #ddd solid;
    border-top: 4px #2e93e6 solid;
    border-radius: 50%;
    animation: sp-anime 0.8s infinite linear;
}
@keyframes sp-anime {
    100% {
        transform: rotate(360deg);
    }
}
