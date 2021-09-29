export {showError,checkName,checkEmail,checkPass,checkRePass,checkInput};
function showError(message)
{
    return `<div class="text-notify">
            <i class="fas fa-exclamation"></i>
            <span>${message}</span>
        </div>`;
}

function checkName(value,el,parent)
{
    let regex = /^[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵýỷỹ\s|]{3,50}$/i;
    if(!regex.test(value.trim()))
    {
        parent.append(showError('Name contains only a-z, from 3 to 50 characters'));
        el.addClass('is-invalid');
        return false;
    }
    el.addClass('is-valid');
    return true;
}

function checkEmail(value,el,parent)
{
    let regex = /^[a-z0-9_\.]+@[a-z]{3,5}(\.[a-z]{2,3}){1,2}$/i;
    if(!regex.test(value))
    {
        parent.append(showError('Email contains only (a-z), (0-9), (._)'));
        el.addClass('is-invalid');
        return false;
    }
    el.addClass('is-valid');
    return true;
}

function checkPass(value,el,parent)
{
    let regex = /^\w{6,20}$/i;
    if(!regex.test(value))
    {
        parent.append(showError('Password contains only (a-z), (0-9), (_), from 6-20 characters'));
        el.addClass('is-invalid');
        return false;
    }
    el.addClass('is-valid');
    return true;
}

function checkCode(value,el,parent)
{
    let regex = /^\d{6}$/i;
    if(!regex.test(value))
    {
        parent.append(showError('Code contains only (0-9) and 6 characters'));
        el.addClass('is-invalid');
        return false;
    }
    el.addClass('is-valid');
    return true;
}

function checkRePass(value,el,parent)
{
    if(el.val() == $('input[name="password"]').val() && value.trim() !== '')
    {
        el.addClass('is-valid');
        return true;
    }
    parent.append(showError('Passwords do not match'));
    el.addClass('is-invalid');
    return false;
}

function checkInput(el)
{
    el.removeClass('is-invalid is-valid');
    let next = el.next();
    next.remove();
    let parent = el.parent('.form-group');
    let value = el.val();
    let name = el.attr('name');
    switch(name)
    {
        case 'fullname':
            return checkName(value,el,parent);
            break;
        case 'email':
            return checkEmail(value,el,parent);
            break;
        case 'password':
            return checkPass(value,el,parent);
            break;
        case 're-password':
            return checkRePass(value,el,parent);
            break;
        case 'code':
            return checkCode(value,el,parent);
            break;
        default:
            return false;
    }
}