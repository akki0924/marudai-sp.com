.red {
    color: #FF0000;
}
.btn {
    cursor: pointer;
}
.ctr {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
/*==================================================================================
form
==================================================================================*/
.form_error {
    margin-left: 1em;
    color: #FF0000;
    font-weight: bold;
}
.form_error:before {
    content: "*";
    margin-left: -1em;
}
.form_btn {
    cursor: pointer;
    cursor: hand;
}
a.form_btn {
    position: relative;
    display: inline-block;
    padding: 0.5rem 2rem;
    margin: 0.1rem;
    border: 1px solid #eee;
    border-radius: 0.5rem;
    background-color: #f3f3f3;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
a.form_btn:hover {
    background-color: #fdfbfb;
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
