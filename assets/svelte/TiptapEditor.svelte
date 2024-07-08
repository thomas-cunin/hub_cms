<script>
    import {onMount} from 'svelte';
    import {Editor} from '@tiptap/core';
    import StarterKit from '@tiptap/starter-kit';
    import Image from '@tiptap/extension-image';
    import {writable} from 'svelte/store';

    let editor;
    let editorContainer;

    const isBold = writable(false);
    const isItalic = writable(false);
    const isStrike = writable(false);

    onMount(() => {
        editor = new Editor({
            element: editorContainer,
            extensions: [
                StarterKit,
                Image.configure({
                    inline: true,
                }),
            ],
            content: '<p>Commencez à écrire...</p>',
            onUpdate: updateButtonStates,
            onTransaction: updateButtonStates,
        });
    });

    function updateButtonStates() {
        isBold.set(editor.isActive('bold'));
        isItalic.set(editor.isActive('italic'));
        isStrike.set(editor.isActive('strike'));
    }

    function toggleBold() {
        editor.chain().focus().toggleBold().run();
        // updateButtonStates();
    }

    function toggleItalic() {
        editor.chain().focus().toggleItalic().run();
        // updateButtonStates();
    }

    function toggleStrike() {
        editor.chain().focus().toggleStrike().run();
        // updateButtonStates();
    }

    function insertHeading(level) {
        editor.chain().focus().toggleHeading({level}).run();
        // updateButtonStates();
    }

    function insertImage() {
        const url = prompt('URL de l\'image');
        if (url) {
            editor.chain().focus().setImage({src: url}).run();
        }
        // updateButtonStates();
    }

    function insertBulletList() {
        editor.chain().focus().toggleBulletList().run();
        // updateButtonStates();
    }

    function insertOrderedList() {
        editor.chain().focus().toggleOrderedList().run();
        // updateButtonStates();
    }

    function insertHorizontalRule() {
        editor.chain().focus().setHorizontalRule().run();
        // updateButtonStates();
    }

    function insertBlockquote() {
        editor.chain().focus().toggleBlockquote().run();
        // updateButtonStates();
    }
</script>

<div>
    <div class="toolbar">
        <button type="button" class:active={$isBold} on:click={toggleBold}><strong>B</strong></button>
        <button type="button" class:active={$isItalic} on:click={toggleItalic}><em>I</em></button>
        <button type="button" class:active={$isStrike} on:click={toggleStrike}><s>S</s></button>
        <button type="button" on:click={() => insertHeading(1)}>H1</button>
        <button type="button" on:click={() => insertHeading(2)}>H2</button>
        <button type="button" on:click={insertBulletList}>• List</button>
        <button type="button" on:click={insertOrderedList}>1. List</button>
        <button type="button" on:click={insertHorizontalRule}>HR</button>
        <button type="button" on:click={insertBlockquote}>&ldquo; Blockquote</button>
        <button type="button" on:click={insertImage}>Image</button>
    </div>
    <div bind:this={editorContainer} class="editor"></div>
</div>
