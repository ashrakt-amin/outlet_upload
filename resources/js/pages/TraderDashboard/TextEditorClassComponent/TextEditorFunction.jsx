import React, { useState } from "react";
import { EditorState, convertToRaw } from "draft-js";
import { Editor } from "react-draft-wysiwyg";
import draftToHtml from "draftjs-to-html";
import htmlToDraft from "html-to-draftjs";

import "react-draft-wysiwyg/dist/react-draft-wysiwyg.css";

import "./textEditorStyle.scss";
const TextEditorFunction = ({ textEditorValue }) => {
    const [editorStateVal, setEditorStateVal] = useState(
        EditorState.createEmpty()
    );
    const [descVal, setDescVal] = useState("");
    const changeEditor = (editor) => {
        setEditorStateVal(editor);
        setDescVal(draftToHtml(convertToRaw(editor.getCurrentContent())));
        textEditorValue(descVal);
    };

    return (
        <div className="border-2 p-2">
            <h6 className="my-1">إكتب وصف للمنتج</h6>
            <Editor
                editorState={editorStateVal}
                wrapperClassName="demo-wrapper"
                editorClassName="demo-editor"
                placeholder="اكتب هنا"
                onEditorStateChange={changeEditor}
            />
        </div>
    );
};

export default React.memo(TextEditorFunction);
