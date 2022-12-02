import React, { Component } from "react";
import { EditorState, convertToRaw } from "draft-js";
import { Editor } from "react-draft-wysiwyg";
import draftToHtml from "draftjs-to-html";
import htmlToDraft from "html-to-draftjs";

import "react-draft-wysiwyg/dist/react-draft-wysiwyg.css";

import "./textEditorStyle.scss";

export default class TextEditor extends Component {
    state = {
        editorState: EditorState.createEmpty(),
    };

    // const [editorState,setEditorState] = uesState(EditorState.createEmpty())
    // const changeEditor = (editorState)=> {
    // setEditorState({ editorState})
    // }

    onEditorStateChange = (editorState) => {
        this.setState({
            editorState,
        });
    };

    render() {
        const { editorState } = this.state;
        this.props.textValue(
            draftToHtml(convertToRaw(editorState.getCurrentContent()))
        );
        return (
            <div className="border-2 p-2">
                <h6 className="my-1">إكتب وصف للمنتج</h6>
                <Editor
                    editorState={editorState}
                    wrapperClassName="demo-wrapper"
                    editorClassName="demo-editor"
                    placeholder="اكتب هنا"
                    onEditorStateChange={this.onEditorStateChange}
                />
                {/* <textarea
                    disabled
                    value={draftToHtml(
                        convertToRaw(editorState.getCurrentContent())
                    )}
                /> */}
            </div>
        );
    }
}
