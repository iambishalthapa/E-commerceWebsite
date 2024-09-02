def find_hidden_word_acrostic(paragraph):
    sentences = paragraph.split('. ')
    return ''.join([sentence[0] for sentence in sentences if sentence])

def find_hidden_word_initial_letters(paragraph):
    words = paragraph.split()
    return ''.join([word[0] for word in words])

def find_hidden_word_steganography(paragraph, n):
    words = paragraph.split()
    return ''.join([word[n-1] for word in words if len(word) >= n])

def find_hidden_word_capitalization(paragraph):
    return ''.join([char for char in paragraph if char.isupper()])

# Example paragraph
paragraph = "Cats are great pets. Having one can be very rewarding. All you need is to give them love. Toys can keep them entertained. Give them a comfortable home. Play with them regularly. They will appreciate it."

print("Acrostic method:", find_hidden_word_acrostic(paragraph))
print("Initial letters method:", find_hidden_word_initial_letters(paragraph))
print("Steganography method (2nd letter):", find_hidden_word_steganography(paragraph, 2))
print("Capitalization method:", find_hidden_word_capitalization(paragraph))
