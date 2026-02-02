// renderer.js
import { ToastRenderer } from './components/toast.js';
import { FormHandler } from './components/form.js';
import { TableManager } from './components/table.js';
import LetterAvatar from './components/letter-avatar.js';
import { Select2Wrapper } from './components/select-2.js';
import { SummernoteEditor } from './components/summernote.js';

window.App = {
    Toast           : new ToastRenderer(),
    Form            : FormHandler,
    TableManager    : TableManager,
    LetterAvatar    : LetterAvatar,
    Select2Wrapper  : Select2Wrapper,
    SummernoteEditor    : SummernoteEditor
};
