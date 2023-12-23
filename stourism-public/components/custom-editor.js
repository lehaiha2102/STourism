import React, { useEffect, useState } from 'react';
import { CKEditor } from "@ckeditor/ckeditor5-react";
import Editor from "ckeditor5-custom-build";

const editorConfiguration = {
    toolbar: [
        'heading',
        '|',
        'bold',
        'italic',
        'link',
        'bulletedList',
        'numberedList',
        '|',
        'outdent',
        'indent',
        '|',
        'blockQuote',
        'insertTable',
        'mediaEmbed',
        'undo',
        'redo'
    ],
};

function CustomEditor( props ) {
    const {getContent} = props;
    const [data, setData] = useState('');
    useEffect(()=>{
        getContent(data);
    }, [data])
    // getContent(data)
        return (
            <CKEditor
                editor={ Editor }
                config={ editorConfiguration }
                data={ props.initialData }
                onChange={ (event, editor ) => {
                    const data = editor.getData();
                    setData(data);
                } }
            />
        )
}

export default CustomEditor;
