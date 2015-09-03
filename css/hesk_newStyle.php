.nu-rtlFloatLeft {
    /* Don't do anything; the pager looks good already */
}

.nu-floatRight {
    float: right;
}

.tabPadding {
    padding: 10px;
}

@media (max-width:991px) {
    .close-ticket {
        text-align: left;
    }
}

@media (min-width:992px) {
    .close-ticket {
        text-align: right;
        padding-bottom: 5px;
    }
}


@media (max-width:991px) {
    .ticket-cell {
        border-bottom: solid 1px #ddd;
        border-right: 0;
        padding-top: 5px;
    }
}

@media (max-width:991px) {
    .ticket-cell-admin {
        border-bottom: solid 1px #ddd;
        border-right: 0;
        padding-top: 5px;
        height: 100px;
    }
}

@media (min-width:992px) {
    .ticket-cell {
        border-bottom: 0;
        border-right: solid 1px #ddd;
        margin-top: 1px;
        min-height: 70px;
        padding-top: 10px;
    }
}

@media (min-width:992px) {
    .ticket-cell-admin {
        border-bottom: 0;
        border-right: solid 1px #ddd;
        margin-top: 1px;
        height: 100px;
        padding-top: 10px;
    }    
}

.row {
    margin-left: 0px;
    margin-right: 0px;
}

.navbar {
    margin-bottom: 0;
}
.h3questionmark:hover {
    text-decoration: underline;
}
.form-signin {
    max-width: 800px;
    margin: 0 auto;
}
.loginError {
    max-width: 800px;
    padding-top: 20px;
    margin-left: auto;
    margin-right: auto;
}
.kbContent {
    padding-top: 10px;
    text-align: left;
}
.withBorder {
    border-bottom: 1px solid #ddd;
}
.ticketMessageContainer {
    background-color: #ededef;
    border: 1px solid #ddd;
    margin-bottom: 20px;
}
.ticketName {
    font-size: 20px;
    font-weight: 300;
    color: #000;
    margin-top: 5px;
}
.ticketEmail {
    font-size: 14px;
    color: #888;
}
.ticketMessageTop {
    padding-top: 10px;
    padding-left: 10px;
    padding-right: 10px;
    margin-right: -15px;
    color: #888;
    background-color: #fff;
}

.pushMargin {
    margin-top: -10px;
    margin-bottom: -10px;
}

.pushMarginLeft {
    margin-left: -15px;
    margin-right: -15px;
    padding-right: 0;
}

.ticketMessageBottom {
    padding-left: 10px;
    padding-right: 10px;
    margin-right: -15px;
    word-wrap: break-word;
    font-size: 15px;
    background-color: #fff;
}
.ticketMessageBottom > .message {
    margin-bottom: 0px;

}

.message > * {
    margin-top: 0;
    margin-bottom: 0;
}

.ticketMessage {
    margin-left: 238px;
    background: #fff;
    height: 100%;
    position: relative;
}
.ticketPropertyTitle {
    color: rgba(255, 255, 255, .75);
    font-size: 11px;
    text-transform: uppercase;
}

@media (min-width: 992px) {
    .ticketPropertyText {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .ticketPropertyText:hover {
        white-space: normal;
        overflow: none;
    }
}

.ticketPropertyText {
    font-size: 16px;
    line-height: 1em;
    color: #fff;
    padding-bottom: 2px;
}

.criticalPriority {
    background-color: red;
}
.highPriority {
    background-color: #ff6a00;
}
.medLowPriority {
    background-color: #8BB467;
}
div.blankSpace {
    padding-top: 20px;
}
div.footerWithBorder {
    border-top: 1px solid #cfd4d6;
}
div.rightSideDash {
    padding-left: 18px;
    padding-right: 18px;
}
div.enclosing {
    background-color: #fff;
    color: #4a5571;
    font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
    font-size: 12px;
    width: 100%}
div.headersm {
    width: 100%;
    color: #fff;
    font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
    font-size: 12px;
    text-align: left;
    background-color: #424b5c;
    background-repeat: repeat-x;
    padding: 12px 20px 8px;
    margin: 0;
    font-weight: 700;
    padding-left: 20px;
    background-image: none;
    height: auto;
}
div.installWarning {
    width: 70%;
    height: 52px;
    margin-top: 10px;
    margin-left: auto;
    margin-right: auto;
}
div.setupContainer {
    margin: 50px;
    text-align: center;
}
div.setupLogo {
    vertical-align: middle;
    border: 0;
    margin-top: -2px;
}
div.setupButtons {
    text-decoration: none;
    border: 4px solid #eee;
    background: #fff;
    border-radius: 5px;
    color: #61718c;
    -webkit-box-shadow: rgba(0, 0, 0, .1)0 0 3px;
    -moz-box-shadow: rgba(0, 0, 0, .1)0 0 3px;
    text-align: center;
    margin: 20px 0;
    padding: 10px 0;
}
.agreementBox {
    position: relative;
    background-color: #fff;
    overflow: auto;
    padding: 20px;
    display: block;
    height: 206px;
    box-shadow: inset 0 0 4px #bbb, inset 0 0 20px #eee;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
.summaryList {
    border-style: solid;
    border-width: 1px;
    border-color: #ddd;
    border-top-color: transparent;
}
.installRequirements {
    margin-left: auto;
    margin-right: auto;
    width: 90%;
}

.white-readonly {
    cursor: text !important;
    background-color: #fff !important;
}

button.btn.dropdown-toggle {
    height: 34px;
}

button.dropdown-submit {
    background:none!important;
    border:none;
}

.attachment-table > tbody > tr > td > i {
    color: #ddd;
    text-shadow: 2px 2px #ccc;
}

.attachment-table > tbody > tr > td {
    vertical-align: middle;
}

.attachment-table > tbody > tr > td > span > img {
    max-height: 80px;
    max-width: 80px;
    cursor: pointer;
}

.plaintext-editor {
    font-family: monospace;
}

.table-fixed {
    table-layout: fixed;
}

.indent-15 {
    margin-left: 15px;
}

.button-link {
    color: #4a5571;
}

.button-link:hover {
    text-decoration: none;
    color: #000;
}

.button-link .col-xs-1 {
    margin: 0 auto;
    padding: 0;
}

.button-link .panel-body:hover {
    background-color: #EEE;
}

.default-row-margins {
    margin: 0 -15px;
}

.icon-link {
    font-size: 16px !important;
}

.orange {
    color: orange;
}

.red {
    color: #FF0000;
}

.gray {
    color: gray;
}