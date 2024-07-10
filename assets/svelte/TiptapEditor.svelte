<script>
    import {onMount} from 'svelte';
    import {Editor} from '@tiptap/core';
    import StarterKit from '@tiptap/starter-kit';
    import Image from '@tiptap/extension-image';
    import BubbleMenu from '@tiptap/extension-bubble-menu';
    import {Color} from '@tiptap/extension-color';
    import TextAlign from '@tiptap/extension-text-align';
    import FontFamily from '@tiptap/extension-font-family';
    import {TextStyle} from '@tiptap/extension-text-style';
    // import {TaskList} from '@tiptap/extension-task-list';
    // import {TaskItem} from '@tiptap/extension-task-item';
    import {writable} from 'svelte/store';

    let editor;
    let editorContainer;
    let bubbleMenuContainer;

    const isBold = writable(false);
    const isItalic = writable(false);
    const isStrike = writable(false);
    const isParagraph = writable(false);
    const isTextAlignLeft = writable(false);
    const isTextAlignCenter = writable(false);
    const isTextAlignRight = writable(false);
    const isTextAlignJustify = writable(false);
    const isText = writable(false);
    const isHeading1 = writable(false);
    const isHeading2 = writable(false);
    const isHeading3 = writable(false);
    const isBulletList = writable(false);
    const isOrderedList = writable(false);
    const isBlockquote = writable(false);
    const currentColor = writable('#000000');
    const currentFontFamily = writable(false);

    let localColor = '#000000';

    onMount(() => {
        editor = new Editor({
            element: editorContainer,
            extensions: [
                StarterKit,
                Image.configure({
                    inline: true,
                }),
                BubbleMenu.configure({
                    element: bubbleMenuContainer,
                }),
                TextStyle,
                Color,
                TextAlign.configure({
                    types: ['heading', 'paragraph'],
                }),,
                FontFamily,
            ],
            content: '',
            onUpdate: updateButtonStates,
            onTransaction: updateButtonStates,
        });

        editor.commands.setContent(document.querySelector('#content_page_editor_content').value);
    });

    function updateTextArea() {
        document.querySelector('#content_page_editor_content').value = editor.getHTML();
    }
    function updateButtonStates() {
        updateTextArea();
        isBold.set(editor.isActive('bold'));
        isItalic.set(editor.isActive('italic'));
        isStrike.set(editor.isActive('strike'));
        isParagraph.set(editor.isActive('paragraph'));
        isTextAlignLeft.set(editor.isActive('textAlign', {left: true}));
        isTextAlignCenter.set(editor.isActive('textAlign', {center: true}));
        isTextAlignRight.set(editor.isActive('textAlign', {right: true}));
        isText.set(editor.isActive('text'));
        isHeading1.set(editor.isActive('heading', {level: 1}));
        isHeading2.set(editor.isActive('heading', {level: 2}));
        isHeading3.set(editor.isActive('heading', {level: 3}));

        isBulletList.set(editor.isActive('bulletList'));
        isOrderedList.set(editor.isActive('orderedList'));
        isBlockquote.set(editor.isActive('blockquote'));

        const attrs = editor.getAttributes('textStyle');
        currentColor.set(attrs.color || '#000000');
        localColor = attrs.color || '#000000';
        currentFontFamily.set(attrs.fontFamily);
    }

    function toggleBold() {
        editor.chain().focus().toggleBold().run();
    }

    function toggleItalic() {
        editor.chain().focus().toggleItalic().run();
    }

    function toggleStrike() {
        editor.chain().focus().toggleStrike().run();
    }

    function toggleTextAlignLeft() {
        editor.chain().focus().setTextAlign('left').run();
    }

    function toggleTextAlignCenter() {
        editor.chain().focus().setTextAlign('center').run();
    }

    function toggleTextAlignRight() {
        editor.chain().focus().setTextAlign('right').run();
    }

    function insertHeading(level) {
        editor.chain().focus().toggleHeading({level}).run();
    }

    function insertImage() {
        const url = prompt("URL de l'image");
        if (url) {
            editor.chain().focus().setImage({src: url}).run();
        }
    }

    function insertBulletList() {
        editor.chain().focus().toggleBulletList().run();
    }

    function insertOrderedList() {
        editor.chain().focus().toggleOrderedList().run();
    }

    function insertHorizontalRule() {
        editor.chain().focus().setHorizontalRule().run();
    }

    function insertBlockquote() {
        editor.chain().focus().toggleBlockquote().run();
    }

    function setParagraph() {
        editor.chain().focus().setParagraph().run();
    }

    function setColor(event) {
        const color = event.target.value;
        currentColor.set(color);
        editor.chain().focus().setColor(color).run();
    }

    function getCurrentSelectionTypeIcon() {
        if ($isHeading1) {
            return 'ri-h-1';
        }
        if ($isHeading2) {
            return 'ri-h-2';
        }
        if ($isHeading3) {
            return 'ri-h-3';
        }
        if ($isBulletList) {
            return 'ri-list-unordered';
        }
        if ($isOrderedList) {
            return 'ri-list-ordered-2';
        }
    }

    function handleDropdownClick(event, action) {
        event.preventDefault();
        action();
    }
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">

<div>
    <div class="toolbar">
        <button type="button" class:active={$isBold} on:click={toggleBold}><strong>B</strong></button>
        <button type="button" class:active={$isItalic} on:click={toggleItalic}><em>I</em></button>
        <button type="button" class:active={$isStrike} on:click={toggleStrike}><s>S</s></button>
        <button type="button" class:active={$isHeading1} on:click={() => insertHeading(1)}>H1</button>
        <button type="button" class:active={$isHeading2} on:click={() => insertHeading(2)}>H2</button>
        <button type="button" class:active={$isHeading3} on:click={() => insertHeading(3)}>H3</button>
        <button type="button" class:active={$isParagraph} on:click={setParagraph}>P</button>
        <button type="button" class:active={$isBulletList} on:click={insertBulletList}>UL</button>
        <button type="button" class:active={$isOrderedList} on:click={insertOrderedList}>OL</button>
        <button type="button" on:click={insertImage}>IMG</button>
        <button type="button" on:click={insertHorizontalRule}>HR</button>
        <button type="button" class:active={$isBlockquote} on:click={insertBlockquote}>BLOCKQUOTE</button>
        <button type="button" class:active={$isTextAlignLeft} on:click={toggleTextAlignLeft}><i
                class="ri-align-left"></i></button>
        <button type="button" class:active={$isTextAlignCenter} on:click={toggleTextAlignCenter}><i
                class="ri-align-center"></i></button>
        <button type="button" class:active={$isTextAlignRight} on:click={toggleTextAlignRight}><i
                class="ri-align-right"></i></button>
        <input type="color" value={$currentColor} on:change={setColor}/>
    </div>
    <div bind:this={editorContainer} class="editor"></div>
</div>
